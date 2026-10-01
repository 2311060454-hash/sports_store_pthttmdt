<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\OrderService;
use Exception;
use Illuminate\Http\Request;

class AdminOrderController extends Controller
{
    protected OrderService $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function index(Request $request)
    {
        $query = Order::with('user');

        if ($request->filled('q')) {
            $q = trim($request->q);
            $query->where(function ($sub) use ($q) {
                $sub->where('order_code', 'like', "%{$q}%")
                    ->orWhere('customer_name', 'like', "%{$q}%")
                    ->orWhere('customer_phone', 'like', "%{$q}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        $orders = $query->latest()->paginate(15)->withQueryString();

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load(['user', 'items.product', 'items.variant', 'reviews']);

        $statusOrder = [
            'pending' => 1,
            'confirmed' => 2,
            'preparing' => 3,
            'shipping' => 4,
            'completed' => 5,
            'cancelled' => -1,
        ];
        $currentStep = $statusOrder[$order->status] ?? 1;

        return view('admin.orders.show', compact('order', 'currentStep'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,preparing,shipping,completed,cancelled',
        ]);

        try {
            $this->orderService->updateStatus($order, $request->status);
            return back()->with('success', 'Cập nhật trạng thái đơn hàng #' . $order->order_code . ' thành công!');
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function invoice(Order $order)
    {
        $order->load(['user', 'items.product', 'items.variant']);

        return view('admin.orders.invoice', compact('order'));
    }
}
