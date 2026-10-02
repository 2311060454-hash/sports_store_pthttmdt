<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PaymentTransaction;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminLabFeaturesTest extends TestCase
{
    use RefreshDatabase;

    protected function getAdminUser()
    {
        return User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Admin Test',
                'password' => bcrypt('password123'),
                'role' => 'admin',
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );
    }

    public function test_admin_orders_tabs_and_cancellation_guard()
    {
        $admin = $this->getAdminUser();

        // Create an order in 'delivering' status
        $order = Order::create([
            'order_code' => 'TEST_DELIVERING_123',
            'user_id' => $admin->id,
            'customer_name' => 'Nguyen Van A',
            'customer_email' => 'nguyenvana@gmail.com',
            'customer_phone' => '0901234567',
            'shipping_address' => '123 Le Loi, Q1, HCM',
            'subtotal' => 500000,
            'shipping_fee' => 30000,
            'total_amount' => 530000,
            'payment_method' => 'cod',
            'payment_status' => 'unpaid',
            'status' => 'shipping',
            'shipping_status' => 'delivering',
        ]);

        // Attempting to cancel while delivering should be blocked
        $response = $this->actingAs($admin)->post(route('admin.orders.cancel', $order->id), [
            'cancel_reason' => 'Khách đổi ý',
        ]);

        $response->assertSessionHas('error');
        $this->assertEquals('shipping', $order->fresh()->status);

        // Access orders index tab
        $response = $this->actingAs($admin)->get(route('admin.orders.index', ['tab' => 'delivering']));
        $response->assertStatus(200);
        $response->assertSee('TEST_DELIVERING_123');
    }

    public function test_admin_users_crud()
    {
        $admin = $this->getAdminUser();

        // 1. Index
        $response = $this->actingAs($admin)->get(route('admin.users.index'));
        $response->assertStatus(200);

        // 2. Create form
        $response = $this->actingAs($admin)->get(route('admin.users.create'));
        $response->assertStatus(200);

        // 3. Store
        $testEmail = 'newuser_' . time() . '@example.com';
        $response = $this->actingAs($admin)->post(route('admin.users.store'), [
            'name' => 'User Created By Admin',
            'email' => $testEmail,
            'password' => 'secret123',
            'role' => 'user',
        ]);
        $response->assertRedirect(route('admin.users.index'));
        $newUser = User::where('email', $testEmail)->first();
        $this->assertNotNull($newUser);

        // 4. Edit form
        $response = $this->actingAs($admin)->get(route('admin.users.edit', $newUser->id));
        $response->assertStatus(200);

        // 5. Update
        $response = $this->actingAs($admin)->put(route('admin.users.update', $newUser->id), [
            'name' => 'User Updated Name',
            'email' => $testEmail,
            'role' => 'admin',
        ]);
        $response->assertRedirect(route('admin.users.index'));
        $this->assertEquals('User Updated Name', $newUser->fresh()->name);
        $this->assertEquals('admin', $newUser->fresh()->role);

        // 6. Delete
        $response = $this->actingAs($admin)->delete(route('admin.users.destroy', $newUser->id));
        $response->assertRedirect(route('admin.users.index'));
        $this->assertNull(User::find($newUser->id));
    }

    public function test_admin_reports_and_charts()
    {
        $admin = $this->getAdminUser();

        // 1. Reports Index (table)
        $response = $this->actingAs($admin)->get(route('admin.reports.index'));
        $response->assertStatus(200);
        $response->assertSee('Báo Cáo Doanh Thu');

        // 2. Reports Charts
        $response = $this->actingAs($admin)->get(route('admin.reports.charts'));
        $response->assertStatus(200);
        $response->assertSee('Biểu Đồ Báo Cáo Doanh Thu');
    }

    public function test_admin_finance_and_cod_transitions()
    {
        $admin = $this->getAdminUser();

        // 1. Finance Index
        $response = $this->actingAs($admin)->get(route('admin.finance.index'));
        $response->assertStatus(200);
        $response->assertSee('Thống Kê Tài Chính');

        // 2. Finance Transactions
        $response = $this->actingAs($admin)->get(route('admin.finance.transactions'));
        $response->assertStatus(200);
        $response->assertSee('Giao dịch thanh toán');

        // 3. Test COD Status Update via updateStatus
        $order = Order::create([
            'order_code' => 'TEST_COD_' . time(),
            'user_id' => $admin->id,
            'customer_name' => 'Tran Thi B',
            'customer_email' => 'tranthib@gmail.com',
            'customer_phone' => '0912345678',
            'shipping_address' => '456 Tran Phu, Da Nang',
            'subtotal' => 320000,
            'shipping_fee' => 30000,
            'total_amount' => 350000,
            'payment_method' => 'cod',
            'payment_status' => 'unpaid',
            'status' => 'pending',
            'shipping_status' => 'pending',
        ]);

        $response = $this->actingAs($admin)->patch(route('admin.finance.update-status', $order->id), [
            'payment_status' => 'paid',
            'current_payment_status' => 'pending',
            'current_order_status' => 'pending',
            'current_payment_id' => 0,
        ]);

        $response->assertSessionHas('success');
        $this->assertEquals('paid', $order->fresh()->payment_status);
        $this->assertDatabaseHas('payment_transactions', [
            'order_id' => $order->id,
            'gateway' => 'cod',
            'status' => 'paid',
        ]);
    }
}
