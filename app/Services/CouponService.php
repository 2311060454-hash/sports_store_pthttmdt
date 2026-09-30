<?php

namespace App\Services;

use App\Models\Coupon;

class CouponService
{
    /**
     * Validate a coupon code against a subtotal.
     */
    public function validate(string $code, float $subtotal): array
    {
        $code = strtoupper(trim($code));
        $coupon = Coupon::where('code', $code)->first();

        if (!$coupon) {
            return [
                'valid' => false,
                'message' => 'Mã giảm giá không tồn tại trong hệ thống.',
                'coupon' => null,
            ];
        }

        $check = $coupon->isValidForAmount($subtotal);
        if (!$check['valid']) {
            return [
                'valid' => false,
                'message' => $check['message'],
                'coupon' => null,
            ];
        }

        $discount = $coupon->calculateDiscount($subtotal);

        return [
            'valid' => true,
            'message' => 'Áp dụng mã giảm giá ' . $coupon->code . ' thành công!',
            'coupon' => $coupon,
            'discount' => $discount,
        ];
    }

    /**
     * Increment the coupon usage count.
     */
    public function incrementUsage(?string $code): void
    {
        if (!$code) {
            return;
        }

        $coupon = Coupon::where('code', strtoupper(trim($code)))->first();
        if ($coupon) {
            $coupon->increment('used');
        }
    }
}
