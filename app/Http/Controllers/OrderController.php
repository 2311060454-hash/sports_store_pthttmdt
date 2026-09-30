<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\OrderService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    protected OrderService $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function index()
    {
        $orders = Auth::user()->orders()
            ->with(['items.product'])
            ->latest()
            ->paginate(10);

        return view('frontend.account.orders', compact('orders'));
    }

    public function show(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Bạn không có quyền xem đơn hàng này.');
        }

        $order->load(['items.product', 'items.variant', 'reviews']);

        // Determine timeline step (1: pending, 2: confirmed, 3: preparing, 4: shipping, 5: completed, -1: cancelled)
        $statusOrder = [
            'pending' => 1,
            'confirmed' => 2,
            'preparing' => 3,
            'shipping' => 4,
            'completed' => 5,
            'cancelled' => -1,
        ];
        $currentStep = $statusOrder[$order->status] ?? 1;

        return view('frontend.account.order-detail', compact('order', 'currentStep'));
    }

    public function cancel(Request $request, Order $order)
    {
        $request->validate([
            'reason' => 'required|string|max:500',
        ], [
            'reason.required' => 'Vui lòng cung cấp lý do hủy đơn hàng.',
        ]);

        try {
            $this->orderService->cancelOrder($order, $request->input('reason'), Auth::user());
            return back()->with('success', 'Đơn hàng #' . $order->order_code . ' đã được hủy thành công. Số lượng sản phẩm đã được hoàn lại kho.');
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
