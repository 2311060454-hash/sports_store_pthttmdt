<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'subject',
        'message',
        'status',
        'admin_notes',
    ];

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'unread' => '<span class="badge bg-danger">Chưa đọc</span>',
            'read' => '<span class="badge bg-info text-dark">Đã xem</span>',
            'replied' => '<span class="badge bg-success">Đã phản hồi</span>',
            default => '<span class="badge bg-secondary">' . $this->status . '</span>',
        };
    }
}
