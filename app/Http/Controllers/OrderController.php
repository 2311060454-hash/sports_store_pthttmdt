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

    public function invoice(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Bạn không có quyền xem hóa đơn này.');
        }

        $order->load(['user', 'items.product', 'items.variant']);

        return view('frontend.account.invoice', compact('order'));
    }

    /**
     * Public Order Tracking page (GET /order-tracking)
     */
    public function trackOrderForm(Request $request)
    {
        $code = trim((string) $request->input('code'));
        $phone = trim((string) $request->input('phone'));
        $order = null;
        $currentStep = 1;

        if (!empty($code)) {
            $query = Order::with(['items.product', 'items.variant', 'paymentTransactions'])
                ->where('order_code', $code);

            if (!empty($phone)) {
                $query->where(function ($q) use ($phone) {
                    $q->where('customer_phone', $phone)
                      ->orWhere('customer_phone', 'like', "%{$phone}");
                });
            }

            $order = $query->first();

            if (!$order) {
                return view('frontend.orders.tracking', [
                    'order' => null,
                    'searched' => true,
                    'code' => $code,
                    'phone' => $phone,
                    'error' => 'Không tìm thấy đơn hàng phù hợp với thông tin đã nhập. Vui lòng kiểm tra lại mã đơn hàng hoặc số điện thoại.',
                ]);
            }

            $statusOrder = [
                'pending' => 1,
                'confirmed' => 2,
                'preparing' => 3,
                'shipping' => 4,
                'delivering' => 4,
                'completed' => 5,
                'delivered' => 5,
                'cancelled' => -1,
            ];
            $currentStep = $statusOrder[$order->status] ?? ($statusOrder[$order->shipping_status] ?? 1);
        }

        return view('frontend.orders.tracking', [
            'order' => $order,
            'searched' => !empty($code),
            'code' => $code,
            'phone' => $phone,
            'currentStep' => $currentStep,
            'error' => null,
        ]);
    }

    /**
     * Handle Tracking Form submission (POST /order-tracking)
     */
    public function trackOrderQuery(Request $request)
    {
        $request->validate([
            'order_code' => 'required|string|max:50',
            'phone' => 'nullable|string|max:20',
        ], [
            'order_code.required' => 'Vui lòng nhập mã đơn hàng của bạn (Ví dụ: SPORT-XXXXXXXX).',
        ]);

        return redirect()->route('orders.tracking', [
            'code' => trim($request->input('order_code')),
            'phone' => trim($request->input('phone')),
        ]);
    }
}
