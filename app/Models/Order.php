<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_code',
        'customer_name',
        'customer_phone',
        'customer_email',
        'shipping_address',
        'note',
        'subtotal',
        'discount_amount',
        'shipping_fee',
        'total_amount',
        'payment_method',
        'payment_status',
        'status',
        'cancel_reason',
        'coupon_code',
    ];

    protected $casts = [
        'subtotal' => 'float',
        'discount_amount' => 'float',
        'shipping_fee' => 'float',
        'total_amount' => 'float',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function canBeCancelled(): bool
    {
        return $this->status === 'pending';
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'Chờ xử lý',
            'confirmed' => 'Đã xác nhận',
            'preparing' => 'Đang chuẩn bị',
            'shipping' => 'Đang giao hàng',
            'completed' => 'Đã hoàn thành',
            'cancelled' => 'Đã hủy',
            default => $this->status,
        };
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'bg-warning text-dark',
            'confirmed' => 'bg-info text-dark',
            'preparing' => 'bg-primary',
            'shipping' => 'bg-secondary',
            'completed' => 'bg-success',
            'cancelled' => 'bg-danger',
            default => 'bg-light text-dark',
        };
    }

    public function getPaymentMethodLabelAttribute(): string
    {
        return match ($this->payment_method) {
            'cod' => 'Thanh toán khi nhận hàng (COD)',
            'bank_transfer' => 'Chuyển khoản ngân hàng',
            'vnpay' => 'VNPay QR / Thẻ nội địa',
            default => strtoupper($this->payment_method),
        };
    }
}
