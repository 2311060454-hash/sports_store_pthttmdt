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

        // Kiểm tra trang Profile hiển thị tốt
        $profileResponse = $this->get('/account/profile');
        $profileResponse->assertStatus(200);
        $profileResponse->assertSee('Thông Tin Cá Nhân');
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

    public function test_ghn_location_endpoints(): void
    {
        $resProvinces = $this->getJson('/locations/provinces');
        $resProvinces->assertStatus(200)
            ->assertJsonStructure(['code', 'data']);

        $resDistricts = $this->getJson('/locations/districts/201');
        $resDistricts->assertStatus(200)
            ->assertJsonStructure(['code', 'data']);

        $resWards = $this->getJson('/locations/wards/1450');
        $resWards->assertStatus(200)
            ->assertJsonStructure(['code', 'data']);

        $resFee = $this->postJson('/locations/calculate-fee', [
            'to_district_id' => 1450,
            'to_ward_code' => '20101',
        ]);
        $resFee->assertStatus(200)
            ->assertJsonStructure(['code', 'data']);
    }

    public function test_admin_can_access_ghn_management_center(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'status' => 'active',
        ]);

        $this->actingAs($admin);

        $res = $this->get('/admin/ghn');
        $res->assertStatus(200)
            ->assertSee('Đồng Bộ Giao Hàng Nhanh')
            ->assertSee('Công Cụ Tính Cước Phí Nhanh');
    }

    public function test_shipping_calculator_page_and_custom_fee(): void
    {
        $pageResponse = $this->get('/shipping-calculator');
        $pageResponse->assertStatus(200)
            ->assertSee('Tra Cứu & Tính Cước Phí Vận Chuyển')
            ->assertSee('Sport Store');

        $feeResponse = $this->postJson('/locations/calculate-custom-fee', [
            'to_district_id' => 1442,
            'to_ward_code' => '20508',
            'weight' => 500,
            'length' => 20,
            'width' => 15,
            'height' => 10,
            'insurance_value' => 300000,
        ]);

        $feeResponse->assertStatus(200)
            ->assertJson([
                'success' => true,
            ])
            ->assertJsonStructure([
                'success',
                'ghn_response',
                'expected_time',
                'expected_date',
                'weight',
            ]);
    }

    public function test_faq_policy_and_blog_pages(): void
    {
        // 1. FAQ page
        $res = $this->get('/faq');
        $res->assertStatus(200)
            ->assertSee('Câu Hỏi Thường Gặp')
            ->assertSee('MoMo');

        // 2. Policy page
        $res = $this->get('/policy');
        $res->assertStatus(200)
            ->assertSee('Chính Sách & Quy Định Hoạt Động', false)
            ->assertSee('30 ngày');

        // 3. Blog index
        $res = $this->get('/blog');
        $res->assertStatus(200)
            ->assertSee('Kiến Thức Thể Thao & Mẹo Tập Luyện', false);

        // 4. Blog detail
        $res = $this->get('/blog/1');
        $res->assertStatus(200)
            ->assertSee('Bí quyết chọn vợt cầu lông');
    }

    public function test_customer_loyalty_tier_on_account(): void
    {
        $user = User::factory()->create([
            'status' => 'active',
        ]);

        $this->actingAs($user);

        $res = $this->get('/account');
        $res->assertStatus(200)
            ->assertSee('Hạng Hội Viên')
            ->assertSee('Điểm tích lũy');
    }
}
