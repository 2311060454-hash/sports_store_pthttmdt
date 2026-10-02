<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckoutRequest;
use App\Models\Order;
use App\Services\CartService;
use App\Services\OrderService;
use App\Services\PaymentService;
use Exception;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    protected CartService $cartService;
    protected OrderService $orderService;
    protected PaymentService $paymentService;

    public function __construct(
        CartService $cartService,
        OrderService $orderService,
        PaymentService $paymentService
    ) {
        $this->cartService = $cartService;
        $this->orderService = $orderService;
        $this->paymentService = $paymentService;
    }

    public function index()
    {
        $cart = $this->cartService->getCart();

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng của bạn đang trống. Vui lòng chọn sản phẩm trước khi thanh toán.');
        }

        $user = Auth::user();
        $addresses = $user->addresses()->latest()->get();
        $defaultAddress = $addresses->firstWhere('is_default', true) ?? $addresses->first();

        $subtotal = $this->cartService->getSubtotal();
        $discount = $this->cartService->getDiscount();
        $shippingFee = $this->cartService->getShippingFee();
        $total = $this->cartService->getTotal();
        $coupon = $this->cartService->getCoupon();

        return view('frontend.checkout.index', compact(
            'cart',
            'user',
            'addresses',
            'defaultAddress',
            'subtotal',
            'discount',
            'shippingFee',
            'total',
            'coupon'
        ));
    }

    public function process(CheckoutRequest $request)
    {
        $user = Auth::user();

        try {
            $order = $this->orderService->createOrder($request->validated(), $user, $this->cartService);

            // Nếu thanh toán qua MoMo, chuyển hướng ngay đến cổng MoMo (theo chuẩn Lab)
            if ($order->payment_method === 'momo') {
                return redirect()->route('orders.momo.start', $order);
            }

            $paymentResult = $this->paymentService->process($order);

            session()->flash('payment_info', $paymentResult);

            return redirect()->route('checkout.success', ['orderCode' => $order->order_code])
                ->with('success', 'Chúc mừng bạn đã đặt hàng thành công!');
        } catch (Exception $e) {
            return back()->withInput()->with('error', 'Đặt hàng thất bại: ' . $e->getMessage());
        }
    }

    public function success(string $orderCode)
    {
        $order = Order::with(['items.product', 'items.variant'])
            ->where('order_code', $orderCode)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $paymentInfo = session('payment_info', $this->paymentService->process($order));

        return view('frontend.checkout.success', compact('order', 'paymentInfo'));
    }
}
