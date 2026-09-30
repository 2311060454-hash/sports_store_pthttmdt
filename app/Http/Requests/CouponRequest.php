<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CouponRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->role === 'admin';
    }

    public function rules(): array
    {
        $couponId = $this->route('coupon')->id ?? $this->route('coupon');

        return [
            'code' => ['required', 'string', 'max:50', 'uppercase', 'unique:coupons,code,' . $couponId],
            'type' => ['required', 'in:percent,fixed'],
            'value' => ['required', 'numeric', 'min:0'],
            'min_order_amount' => ['nullable', 'numeric', 'min:0'],
            'max_discount' => ['nullable', 'numeric', 'min:0'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'quantity' => ['required', 'integer', 'min:1'],
            'status' => ['required', 'in:active,inactive'],
        ];
    }

    public function messages(): array
    {
        return [
            'code.required' => 'Vui lòng nhập mã giảm giá.',
            'code.unique' => 'Mã giảm giá này đã tồn tại.',
            'type.required' => 'Vui lòng chọn loại giảm giá (phần trăm hoặc cố định).',
            'value.required' => 'Vui lòng nhập giá trị giảm giá.',
            'value.numeric' => 'Giá trị giảm giá phải là số hợp lệ.',
            'quantity.required' => 'Vui lòng nhập số lượng mã phát hành.',
            'quantity.min' => 'Số lượng mã phát hành phải lớn hơn 0.',
            'end_date.after_or_equal' => 'Ngày kết thúc phải sau hoặc bằng ngày bắt đầu.',
        ];
    }
}
