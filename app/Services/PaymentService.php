<?php

namespace App\Services;

use App\Models\Order;

class PaymentService
{
    /**
     * Generate or process payment URL / response for the chosen method.
     */
    public function process(Order $order): array
    {
        return match ($order->payment_method) {
            'cod' => [
                'type' => 'success',
                'message' => 'Đơn hàng được ghi nhận thanh toán khi nhận hàng (COD). Vui lòng chuẩn bị tiền mặt khi bưu tá giao hàng.',
                'redirect_url' => null,
            ],
            'bank_transfer' => [
                'type' => 'info',
                'message' => 'Vui lòng chuyển khoản đến số tài khoản ngân hàng của cửa hàng với cú pháp: ' . $order->order_code,
                'bank_details' => [
                    'bank' => 'Vietcombank - Ngân hàng TMCP Ngoại thương Việt Nam',
                    'account_number' => '998877665544',
                    'account_holder' => 'SPORT STORE OFFICIAL',
                    'amount' => $order->total_amount,
                    'syntax' => $order->order_code,
                ],
                'redirect_url' => null,
            ],
            'vnpay' => [
                'type' => 'vnpay',
                'message' => 'Cổng thanh toán điện tử VNPay đã sẵn sàng kết nối.',
                'redirect_url' => null,
            ],
            default => [
                'type' => 'default',
                'message' => 'Đơn hàng đã được tạo thành công.',
                'redirect_url' => null,
            ],
        };
    }
}
