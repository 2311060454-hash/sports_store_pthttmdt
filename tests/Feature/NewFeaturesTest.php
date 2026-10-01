<?php

namespace Tests\Feature;

use App\Models\ChatMessage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NewFeaturesTest extends TestCase
{
    use RefreshDatabase;

    public function test_email_verification_notice_and_instant_verification(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => null,
            'role' => 'customer',
        ]);

        $this->actingAs($user);

        // Truy cập trang thông báo xác thực
        $response = $this->get('/email/verify');
        $response->assertStatus(200);
        $response->assertSee('Xác Thực Địa Chỉ Email');

        // Bấm nút xác thực tức thì (Instant Demo)
        $verifyResponse = $this->post('/email/verify-instant');
        $verifyResponse->assertRedirect(route('account.profile'));

        $this->assertNotNull($user->fresh()->email_verified_at);
    }

    public function test_customer_can_send_and_retrieve_chat_messages(): void
    {
        // 1. Khách hàng gửi tin nhắn
        $sendResponse = $this->postJson('/chat/send', [
            'message' => 'Xin chào Sport Store, mình cần tư vấn vợt cầu lông!',
        ]);

        $sendResponse->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);

        $this->assertDatabaseHas('chat_messages', [
            'message' => 'Xin chào Sport Store, mình cần tư vấn vợt cầu lông!',
            'sender_type' => 'user',
        ]);

        // 2. Khách hàng lấy tin nhắn
        $getResponse = $this->getJson('/chat/messages');
        $getResponse->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);
    }

    public function test_admin_can_access_chat_center_and_reply(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'status' => 'active',
        ]);

        // Tạo 1 tin nhắn từ khách
        $msg = ChatMessage::create([
            'session_id' => 'test-session-123',
            'sender_type' => 'user',
            'message' => 'Khách hỏi giày chạy bộ',
            'is_read' => false,
        ]);

        $this->actingAs($admin);

        // Admin truy cập màn hình chat
        $indexResponse = $this->get('/admin/chats');
        $indexResponse->assertStatus(200)
            ->assertSee('Live Chat Khách Hàng');

        // Admin trả lời tin nhắn
        $replyResponse = $this->postJson('/admin/chats/test-session-123/reply', [
            'message' => 'Dạ bên mình có mẫu Pegasus và Ultraboost nhé!',
        ]);

        $replyResponse->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);

        $this->assertDatabaseHas('chat_messages', [
            'session_id' => 'test-session-123',
            'sender_type' => 'admin',
            'message' => 'Dạ bên mình có mẫu Pegasus và Ultraboost nhé!',
        ]);
    }
}
