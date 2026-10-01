<?php

namespace Database\Seeders;

use App\Models\ChatMessage;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ChatMessageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customer = User::where('role', 'customer')->first();

        // 1. Phiên chat của khách hàng thành viên
        $memberSessionId = (string) Str::uuid();
        $customerId = $customer ? $customer->id : null;

        ChatMessage::create([
            'session_id' => $memberSessionId,
            'user_id' => $customerId,
            'sender_type' => 'user',
            'message' => 'Chào shop, cho mình hỏi vợt Yonex Astrox 88D Pro bên mình đang có sẵn loại 3U hay 4U vậy ạ?',
            'is_read' => true,
            'created_at' => now()->subMinutes(45),
        ]);

        ChatMessage::create([
            'session_id' => $memberSessionId,
            'user_id' => $customerId,
            'sender_type' => 'admin',
            'message' => 'Dạ chào bạn! Mẫu Yonex Astrox 88D Pro bên shop hiện đang sẵn hàng cả bản 3U (nặng đầu cho người chơi tấn công mạnh) và 4U (linh hoạt công thủ) ạ. Bạn có thể áp thêm mã SPORT10 để được giảm 10% nha!',
            'is_read' => true,
            'created_at' => now()->subMinutes(40),
        ]);

        ChatMessage::create([
            'session_id' => $memberSessionId,
            'user_id' => $customerId,
            'sender_type' => 'user',
            'message' => 'Tuyệt quá, mình vừa đặt đơn hàng giao về Quận 1 rồi, shop giao sớm giúp mình nhé!',
            'is_read' => false,
            'created_at' => now()->subMinutes(15),
        ]);

        // 2. Phiên chat của khách vãng lai
        $guestSessionId = (string) Str::uuid();

        ChatMessage::create([
            'session_id' => $guestSessionId,
            'user_id' => null,
            'sender_type' => 'user',
            'message' => 'Shop ơi, đơn hàng gửi đi Đà Nẵng qua Giao Hàng Nhanh (GHN) thì mất mấy ngày tới nơi ạ?',
            'is_read' => true,
            'created_at' => now()->subHours(2),
        ]);

        ChatMessage::create([
            'session_id' => $guestSessionId,
            'user_id' => null,
            'sender_type' => 'admin',
            'message' => 'Dạ tuyến Hà Nội/TP.HCM đi Đà Nẵng qua dịch vụ GHN chuẩn thì khoảng 2-3 ngày làm việc là nhận được hàng tận nơi bạn nhé!',
            'is_read' => true,
            'created_at' => now()->subHours(2)->addMinutes(5),
        ]);
    }
}
