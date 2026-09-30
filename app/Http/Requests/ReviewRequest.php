<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReviewRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'product_id' => ['required', 'exists:products,id'],
            'order_id' => ['required', 'exists:orders,id'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['required', 'string', 'min:5', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'rating.required' => 'Vui lòng chọn số sao đánh giá (1-5 sao).',
            'rating.min' => 'Điểm đánh giá tối thiểu là 1 sao.',
            'rating.max' => 'Điểm đánh giá tối đa là 5 sao.',
            'comment.required' => 'Vui lòng nhập nội dung đánh giá sản phẩm.',
            'comment.min' => 'Nội dung đánh giá cần ít nhất 5 ký tự.',
        ];
    }
}
