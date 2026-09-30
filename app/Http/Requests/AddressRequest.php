<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddressRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'receiver_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'regex:/^[0-9]{10,11}$/'],
            'province' => ['required', 'string', 'max:100'],
            'district' => ['required', 'string', 'max:100'],
            'ward' => ['required', 'string', 'max:100'],
            'address' => ['required', 'string', 'max:255'],
            'is_default' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'receiver_name.required' => 'Vui lòng nhập tên người nhận.',
            'phone.required' => 'Vui lòng nhập số điện thoại.',
            'phone.regex' => 'Số điện thoại không hợp lệ (10-11 số).',
            'province.required' => 'Vui lòng chọn hoặc nhập Tỉnh/Thành phố.',
            'district.required' => 'Vui lòng chọn hoặc nhập Quận/Huyện.',
            'ward.required' => 'Vui lòng chọn hoặc nhập Phường/Xã.',
            'address.required' => 'Vui lòng nhập địa chỉ cụ thể (số nhà, tên đường).',
        ];
    }
}
