<?php

namespace App\Http\Controllers;

use App\Services\CartService;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    protected CartService $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function apply(Request $request)
    {
        $request->validate([
            'coupon_code' => 'required|string|max:50',
        ], [
            'coupon_code.required' => 'Vui lòng nhập mã giảm giá.',
        ]);

        $res = $this->cartService->applyCoupon($request->coupon_code);

        if (!$res['success']) {
            return back()->with('error', $res['message']);
        }

        return back()->with('success', $res['message']);
    }

    public function remove()
    {
        $this->cartService->removeCoupon();
        return back()->with('success', 'Đã gỡ mã giảm giá khỏi đơn hàng.');
    }
}
