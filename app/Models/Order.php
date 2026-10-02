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
        'ghn_order_code',
        'ghn_status',
        'ghn_fee',
        'ghn_expected_delivery',
        'ghn_tracking_log',
        'to_district_id',
        'to_ward_code',
        'ghn_total_fee',
        'shipping_status',
    ];

    protected $casts = [
        'subtotal' => 'float',
        'discount_amount' => 'float',
        'shipping_fee' => 'float',
        'total_amount' => 'float',
        'ghn_fee' => 'float',
        'ghn_total_fee' => 'integer',
        'to_district_id' => 'integer',
        'ghn_expected_delivery' => 'datetime',
        'ghn_tracking_log' => 'array',
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

    public function paymentTransactions()
    {
        return $this->hasMany(PaymentTransaction::class);
    }

    public function getTotalPriceAttribute(): float
    {
        return (float) ($this->total_amount ?? 0);
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
            'momo' => 'Ví điện tử MoMo / Thẻ ATM',
            'bank_transfer' => 'Chuyển khoản ngân hàng',
            'vnpay' => 'VNPay QR / Thẻ nội địa',
            default => strtoupper($this->payment_method),
        };
    }

    public function getGhnStatusLabelAttribute(): string
    {
        return match ($this->ghn_status) {
            'ready_to_pick' => 'Chờ lấy hàng',
            'picking' => 'Đang lấy hàng',
            'delivering' => 'Đang giao hàng',
            'delivered' => 'Giao thành công',
            'cancel' => 'Đã hủy vận đơn',
            default => $this->ghn_status ? ucfirst(str_replace('_', ' ', $this->ghn_status)) : 'Chưa tạo',
        };
    }

    public function getGhnStatusBadgeAttribute(): string
    {
        return match ($this->ghn_status) {
            'ready_to_pick' => 'bg-warning text-dark',
            'picking' => 'bg-info text-dark',
            'delivering' => 'bg-primary text-white',
            'delivered' => 'bg-success text-white',
            'cancel' => 'bg-danger text-white',
            default => 'bg-secondary text-white',
        };
    }
}
