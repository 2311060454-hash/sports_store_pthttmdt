<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\GHNOrderService;
use App\Services\GHNService;
use App\Services\OrderService;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class OrderController extends Controller
{
    private const TABS = [
        'all' => ['label' => 'Tất cả', 'color' => 'blue', 'statuses' => []],
        'pending' => ['label' => 'Chờ xử lý', 'color' => 'slate', 'statuses' => ['pending', 'not_shipped', 'processing']],
        'ready' => ['label' => 'Chờ lấy hàng', 'color' => 'cyan', 'statuses' => ['ready_to_pick']],
        'picking' => ['label' => 'Đang lấy hàng', 'color' => 'cyan', 'statuses' => ['picking']],
        'delivering' => ['label' => 'Đang giao', 'color' => 'amber', 'statuses' => ['delivering', 'picked', 'storing', 'transporting', 'sorting']],
        'delivered' => ['label' => 'Thành công', 'color' => 'green', 'statuses' => ['delivered']],
        'return' => ['label' => 'Hoàn hàng', 'color' => 'orange', 'statuses' => ['return', 'returning', 'returned', 'return_transporting', 'return_sorting']],
        'cancelled' => ['label' => 'Đã hủy', 'color' => 'red', 'statuses' => ['cancelled']],
    ];

    /**
     * Hiển thị danh sách đơn hàng cho Admin với Tabs và Filters (chuẩn Lab Admin)
     */
    public function index(Request $request)
    {
        $paymentLabels = [
            'pending' => 'Chờ thanh toán',
            'initiated' => 'Đang chờ MoMo',
            'paid' => 'Đã thanh toán',
            'failed' => 'Thanh toán thất bại',
            'cancelled' => 'Đã hủy',
            'refund_pending' => 'Chờ hoàn tiền',
            'refunded' => 'Đã hoàn tiền',
        ];

        $shippingLabels = [
            'pending' => 'Chờ tạo vận đơn',
            'not_shipped' => 'Chưa giao hàng',
            'processing' => 'Đang tạo vận đơn',
            'ready_to_pick' => 'Chờ lấy hàng',
            'picking' => 'Đang lấy hàng',
            'picked' => 'Đã lấy hàng',
            'storing' => 'Đang lưu kho',
            'transporting' => 'Đang trung chuyển',
            'sorting' => 'Đang phân loại',
            'delivering' => 'Đang giao hàng',
            'delivered' => 'Giao hàng thành công',
            'return' => 'Chờ hoàn hàng',
            'returning' => 'Đang hoàn hàng',
            'returned' => 'Đã hoàn hàng',
            'return_transporting' => 'Đang chuyển hoàn',
            'return_sorting' => 'Đang phân loại hoàn',
            'cancelled' => 'Đã hủy',
        ];

        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'status' => ['nullable', Rule::in(['pending', 'confirmed', 'preparing', 'shipping', 'completed', 'paid', 'paid_momo', 'cod_ordered', 'cod_paid', 'cancelled'])],
            'payment_status' => ['nullable', Rule::in(array_keys($paymentLabels))],
            'shipping_status' => ['nullable', Rule::in(array_keys($shippingLabels))],
            'gateway' => ['nullable', Rule::in(['cod', 'momo', 'bank_transfer', 'vnpay', 'unknown'])],
            'tab' => ['nullable', Rule::in(array_keys(self::TABS))],
            'date_from' => ['nullable', 'date_format:Y-m-d'],
            'date_to' => ['nullable', 'date_format:Y-m-d', ...($request->filled('date_from') ? ['after_or_equal:date_from'] : [])],
            'per_page' => ['nullable', 'integer', Rule::in([15, 25, 50, 100])],
            'sort' => ['nullable', Rule::in(['newest', 'oldest', 'amount_desc', 'amount_asc'])],
            'page' => ['nullable', 'integer', 'min:1'],
        ], [
            'date_to.after_or_equal' => 'Ngày kết thúc phải từ ngày bắt đầu trở đi.',
            '*.date_format' => 'Ngày lọc không hợp lệ.',
            '*.in' => 'Giá trị bộ lọc không hợp lệ.',
        ]);

        $paymentId = DB::table('payment_transactions')
            ->select('id')
            ->whereColumn('order_id', 'orders.id')
            ->orderByRaw("CASE WHEN status IN ('paid', 'refund_pending', 'refunded') THEN 0 ELSE 1 END")
            ->orderByDesc('id')
            ->limit(1);

        $source = DB::table('orders')
            ->leftJoin('payment_transactions as payment', function ($join) use ($paymentId) {
                $join->on('payment.order_id', '=', 'orders.id')->where('payment.id', '=', $paymentId);
            })
            ->select('orders.*')
            ->selectRaw("COALESCE(payment.gateway, orders.payment_method, 'unknown') as gateway")
            ->selectRaw("COALESCE(payment.status, orders.payment_status) as payment_status")
            ->selectRaw("COALESCE(orders.shipping_status, orders.ghn_status, 'pending') as shipping_status");

        $query = Order::query()->fromSub($source, 'orders');

        foreach (['status', 'payment_status', 'gateway'] as $field) {
            if ($request->filled($field)) {
                $query->where($field, $filters[$field]);
            }
        }

        if ($request->filled('search')) {
            $search = trim($filters['search']);
            $query->where(function ($q) use ($search) {
                $q->where('customer_name', 'like', '%' . $search . '%')
                    ->orWhere('customer_phone', 'like', '%' . $search . '%')
                    ->orWhere('order_code', 'like', '%' . $search . '%')
                    ->orWhere('ghn_order_code', 'like', '%' . $search . '%')
                    ->orWhereHas('items', function ($itemQ) use ($search) {
                        $itemQ->where('product_name', 'like', '%' . $search . '%');
                    });

                if (preg_match('/^(?:#|DH)?0*(\d+)$/i', $search, $matches)) {
                    $q->orWhere('orders.id', $matches[1]);
                }
            });
        }

        if ($request->filled('date_from')) {
            $query->where('created_at', '>=', Carbon::parse($filters['date_from'])->startOfDay());
        }

        if ($request->filled('date_to')) {
            $query->where('created_at', '<', Carbon::parse($filters['date_to'])->addDay()->startOfDay());
        }

        // Tính số lượng đơn trên từng tab
        $shippingCounts = (clone $query)->select('shipping_status')
            ->selectRaw('COUNT(*) as total')
            ->groupBy('shipping_status')
            ->pluck('total', 'shipping_status');

        $tabs = collect(self::TABS)->map(function ($tab, $key) use ($shippingCounts) {
            $tab['count'] = $key === 'all'
                ? $shippingCounts->sum()
                : collect($tab['statuses'])->sum(fn ($st) => $shippingCounts->get($st, 0));
            return $tab;
        });

        $activeTab = $filters['tab'] ?? 'all';
        if ($activeTab !== 'all') {
            $query->whereIn('shipping_status', self::TABS[$activeTab]['statuses']);
        }

        if ($request->filled('shipping_status')) {
            $query->where('shipping_status', $filters['shipping_status']);
        }

        [$column, $direction] = match ($filters['sort'] ?? 'newest') {
            'oldest' => ['created_at', 'asc'],
            'amount_desc' => ['total_amount', 'desc'],
            'amount_asc' => ['total_amount', 'asc'],
            default => ['created_at', 'desc'],
        };

        $orders = $query->with(['items.product', 'user'])
            ->orderBy($column, $direction)
            ->orderBy('id', $direction)
            ->paginate((int) ($filters['per_page'] ?? 25))
            ->withQueryString();

        return view('admin.orders.index', compact('orders', 'filters', 'tabs', 'activeTab', 'paymentLabels', 'shippingLabels'));
    }

    /**
     * Hiển thị chi tiết đơn hàng
     */
    public function show($id)
    {
        $order = Order::with(['user', 'items.product', 'items.variant', 'paymentTransactions' => function ($query) {
            $query->latest();
        }])->findOrFail($id);

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

    /**
     * Cập nhật trạng thái đơn hàng (Có kiểm tra: Đang giao -> KHÔNG cho hủy)
     */
    public function updateStatus(Request $request, $id, OrderService $orderService)
    {
        $order = Order::findOrFail($id);

        $request->validate([
            'status' => 'required|in:pending,confirmed,preparing,shipping,completed,cancelled',
        ]);

        $newStatus = $request->status;

        // Quy tắc nghiệp vụ từ Lab: Nếu đơn hàng ở trạng thái đang giao -> KHÔNG cho Hủy
        $deliveringStatuses = ['shipping', 'delivering', 'picked', 'storing', 'transporting', 'sorting'];
        $isDelivering = in_array($order->shipping_status, $deliveringStatuses, true) || $order->status === 'shipping';

        if ($newStatus === 'cancelled' && $isDelivering) {
            return back()->with('error', 'Đơn hàng đang trong quá trình vận chuyển, không được phép Hủy!');
        }

        try {
            $orderService->updateStatus($order, $newStatus);
            return back()->with('success', 'Cập nhật trạng thái đơn hàng #' . $order->order_code . ' thành công!');
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Hủy đơn hàng (Quy tắc nghiệp vụ: Đang giao -> KHÔNG cho hủy)
     */
    public function cancel(Request $request, $id, OrderService $orderService)
    {
        $order = Order::findOrFail($id);

        $deliveringStatuses = ['shipping', 'delivering', 'picked', 'storing', 'transporting', 'sorting'];
        if (in_array($order->shipping_status, $deliveringStatuses, true) || $order->status === 'shipping') {
            return back()->with('error', 'Đơn hàng đang trong quá trình vận chuyển, không được phép Hủy!');
        }

        try {
            $reason = $request->input('cancel_reason', 'Quản trị viên hủy đơn');
            $orderService->cancelOrder($order, $reason, auth()->user());
            return back()->with('success', 'Đã hủy đơn hàng #' . $order->order_code . ' thành công!');
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * In hóa đơn
     */
    public function invoice($id)
    {
        $order = Order::with(['user', 'items.product', 'items.variant'])->findOrFail($id);
        return view('admin.orders.invoice', compact('order'));
    }

    /**
     * Tạo vận đơn GHN thủ công từ trang admin
     */
    public function createGhn($id, GHNOrderService $ghnOrderService)
    {
        $order = Order::findOrFail($id);
        $result = $ghnOrderService->create($order);
        if ($result['success']) {
            return back()->with('success', $result['message']);
        }
        return back()->with('error', $result['message'] ?? 'Tạo vận đơn GHN thất bại.');
    }

    /**
     * Hủy vận đơn GHN thủ công
     */
    public function cancelGhn($id, GHNService $ghnService)
    {
        $order = Order::findOrFail($id);
        if ($order->ghn_order_code) {
            $ghnService->cancelOrder([$order->ghn_order_code]);
        }
        $log = $order->ghn_tracking_log ?? [];
        $log[] = [
            'status' => 'cancel',
            'time' => now()->format('Y-m-d H:i:s'),
            'description' => 'Quản trị viên đã hủy vận đơn GHN.',
        ];
        $order->update([
            'ghn_status' => 'cancel',
            'shipping_status' => 'cancelled',
            'ghn_tracking_log' => $log,
        ]);

        return back()->with('success', 'Đã hủy vận đơn GHN thành công.');
    }
}
