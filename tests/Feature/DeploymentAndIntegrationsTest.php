<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\AdminUserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeploymentAndIntegrationsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test Render Health Check endpoint /up.
     */
    public function test_render_health_check_endpoint(): void
    {
        $response = $this->get('/up');
        $response->assertStatus(200);
    }

    /**
     * Test MoMo IPN webhook endpoint.
     */
    public function test_momo_ipn_webhook_endpoint(): void
    {
        $response = $this->postJson('/payment/momo/ipn', [
            'orderId' => 'SPORT-TEST1234',
            'resultCode' => 0,
            'message' => 'Success',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'resultCode' => 0,
            ]);
    }

    /**
     * Test GHN delivery webhook endpoint.
     */
    public function test_ghn_webhook_endpoint(): void
    {
        $response = $this->postJson('/ghn/webhook', [
            'order_code' => 'SPORT-TEST1234',
            'status' => 'delivering',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'code' => 200,
            ]);
    }

    /**
     * Test AdminUserSeeder creates and verifies admin from config.
     */
    public function test_admin_user_seeder(): void
    {
        config([
            'seeding.admin.name' => 'Render Admin',
            'seeding.admin.email' => 'admin.test@sportsstore.com',
            'seeding.admin.password' => 'SecureAdminPass@123',
        ]);

        $this->seed(AdminUserSeeder::class);

        $admin = User::where('email', 'admin.test@sportsstore.com')->first();
        $this->assertNotNull($admin);
        $this->assertEquals('admin', $admin->role);
        $this->assertNotNull($admin->email_verified_at);
    }
}
