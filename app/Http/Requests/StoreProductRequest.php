<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->role === 'admin';
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'category_id' => ['required', 'exists:categories,id'],
            'brand_id' => ['required', 'exists:brands,id'],
            'sku' => ['required', 'string', 'max:100', 'unique:products,sku'],
            'price' => ['required', 'numeric', 'min:0'],
            'sale_price' => ['nullable', 'numeric', 'min:0', 'lt:price'],
            'quantity' => ['required', 'integer', 'min:0'],
            'short_description' => ['nullable', 'string', 'max:500'],
            'description' => ['nullable', 'string'],
            'thumbnail' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'images.*' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'status' => ['required', 'in:active,inactive'],
            'featured' => ['nullable', 'boolean'],
            'variants' => ['nullable', 'array'],
            'variants.*.size' => ['nullable', 'string', 'max:50'],
            'variants.*.color' => ['nullable', 'string', 'max:50'],
            'variants.*.sku' => ['nullable', 'string', 'max:100'],
            'variants.*.quantity' => ['nullable', 'integer', 'min:0'],
            'variants.*.additional_price' => ['nullable', 'numeric', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Vui lòng nhập tên sản phẩm.',
            'category_id.required' => 'Vui lòng chọn danh mục cho sản phẩm.',
            'category_id.exists' => 'Danh mục được chọn không tồn tại.',
            'brand_id.required' => 'Vui lòng chọn thương hiệu cho sản phẩm.',
            'brand_id.exists' => 'Thương hiệu được chọn không tồn tại.',
            'sku.required' => 'Vui lòng nhập mã SKU sản phẩm.',
            'sku.unique' => 'Mã SKU này đã tồn tại trong hệ thống.',
            'price.required' => 'Vui lòng nhập giá sản phẩm.',
            'price.numeric' => 'Giá sản phẩm phải là một số hợp lệ.',
            'price.min' => 'Giá sản phẩm phải lớn hơn hoặc bằng 0.',
            'sale_price.lt' => 'Giá khuyến mãi phải nhỏ hơn giá gốc.',
            'quantity.required' => 'Vui lòng nhập số lượng tồn kho.',
            'quantity.integer' => 'Số lượng tồn kho phải là số nguyên.',
            'thumbnail.required' => 'Vui lòng chọn ảnh đại diện cho sản phẩm.',
            'thumbnail.image' => 'Ảnh đại diện phải là tệp hình ảnh.',
            'thumbnail.max' => 'Dung lượng ảnh đại diện không quá 2MB.',
        ];
    }
}
