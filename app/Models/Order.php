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

    public function getGhnStatusLabelAttribute(): string
    {
        return \App\Services\GhnService::getStatusLabel($this->ghn_status);
    }

    public function getGhnStatusBadgeAttribute(): string
    {
        return match ($this->ghn_status) {
            'ready_to_pick' => '<span class="badge bg-warning text-dark"><i class="fa-solid fa-box me-1"></i> Chờ lấy hàng</span>',
            'picking' => '<span class="badge bg-info text-dark"><i class="fa-solid fa-truck-pickup me-1"></i> Đang lấy hàng</span>',
            'delivering' => '<span class="badge bg-primary"><i class="fa-solid fa-truck-fast me-1"></i> Đang giao hàng</span>',
            'delivered' => '<span class="badge bg-success"><i class="fa-solid fa-circle-check me-1"></i> Giao thành công</span>',
            'cancel' => '<span class="badge bg-danger"><i class="fa-solid fa-ban me-1"></i> Đã hủy vận đơn</span>',
            default => '<span class="badge bg-secondary">' . ($this->ghn_status ?: 'Chưa tạo') . '</span>',
        };
    }
}
