<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'type',
        'value',
        'min_order_amount',
        'max_discount',
        'start_date',
        'end_date',
        'quantity',
        'used',
        'status',
    ];

    protected $casts = [
        'value' => 'float',
        'min_order_amount' => 'float',
        'max_discount' => 'float',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'quantity' => 'integer',
        'used' => 'integer',
    ];

    public function isValidForAmount(float $subtotal): array
    {
        if ($this->status !== 'active') {
            return ['valid' => false, 'message' => 'Mã giảm giá này hiện không còn hoạt động.'];
        }

        $now = Carbon::now();
        if ($this->start_date && $now->lt($this->start_date)) {
            return ['valid' => false, 'message' => 'Mã giảm giá chưa đến ngày áp dụng.'];
        }

        if ($this->end_date && $now->gt($this->end_date)) {
            return ['valid' => false, 'message' => 'Mã giảm giá đã hết hạn sử dụng.'];
        }

        if ($this->quantity > 0 && $this->used >= $this->quantity) {
            return ['valid' => false, 'message' => 'Mã giảm giá đã hết lượt sử dụng.'];
        }

        if ($subtotal < $this->min_order_amount) {
            return [
                'valid' => false,
                'message' => 'Đơn hàng tối thiểu để áp dụng mã này là ' . number_format($this->min_order_amount, 0, ',', '.') . ' ₫.'
            ];
        }

        return ['valid' => true, 'message' => 'Áp dụng mã giảm giá thành công!'];
    }

    public function calculateDiscount(float $subtotal): float
    {
        if ($this->type === 'percent') {
            $discount = $subtotal * ($this->value / 100);
            if ($this->max_discount !== null && $this->max_discount > 0) {
                $discount = min($discount, $this->max_discount);
            }
            return min($discount, $subtotal);
        }

        return min($this->value, $subtotal);
    }
}
