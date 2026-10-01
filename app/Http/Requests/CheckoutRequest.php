<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_phone' => ['required', 'string', 'regex:/^[0-9]{10,11}$/'],
            'customer_email' => ['required', 'string', 'email', 'max:255'],
            'shipping_address' => ['required', 'string', 'max:500'],
            'to_district_id' => ['nullable', 'integer'],
            'to_ward_code' => ['nullable', 'string', 'max:50'],
            'shipping_fee' => ['nullable', 'numeric', 'min:0'],
            'payment_method' => ['required', 'in:cod,bank_transfer,vnpay'],
            'note' => ['nullable', 'string', 'max:500'],
            'coupon_code' => ['nullable', 'string', 'max:50'],
        ];
    }

    public function messages(): array
    {
        return [
            'customer_name.required' => 'Vui lòng nhập họ và tên người nhận hàng.',
            'customer_phone.required' => 'Vui lòng nhập số điện thoại người nhận.',
            'customer_phone.regex' => 'Số điện thoại người nhận không hợp lệ (10-11 số).',
            'customer_email.required' => 'Vui lòng nhập email để nhận thông báo đơn hàng.',
            'customer_email.email' => 'Địa chỉ email không hợp lệ.',
            'shipping_address.required' => 'Vui lòng nhập đầy đủ địa chỉ giao nhận hàng.',
            'payment_method.required' => 'Vui lòng chọn phương thức thanh toán.',
            'payment_method.in' => 'Phương thức thanh toán đã chọn không hợp lệ.',
        ];
    }
}
