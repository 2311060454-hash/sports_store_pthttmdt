<?php

namespace Tests\Feature;

use App\Mail\OrderConfirmationMail;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class OrderTrackingAndIntegrationsTest extends TestCase
{
    use RefreshDatabase;

    public function test_order_tracking_page_renders_successfully(): void
    {
        $response = $this->get('/order-tracking');
        $response->assertStatus(200);
        $response->assertSee('Tra Cứu Tiến Độ Đơn Hàng');
        $response->assertSee('Mã đơn hàng');
    }

    public function test_order_tracking_with_valid_order_code_and_phone(): void
    {
        $user = User::factory()->create();
        $category = Category::create(['name' => 'Bóng Đá', 'slug' => 'bong-da', 'status' => 'active']);
        $brand = Brand::create(['name' => 'Nike', 'slug' => 'nike', 'status' => 'active']);
        $product = Product::create([
            'category_id' => $category->id,
            'brand_id' => $brand->id,
            'name' => 'Giày Đá Bóng Nike Vapor 15',
            'slug' => 'giay-da-bong-nike-vapor-15',
            'sku' => 'NIKE-VAPOR-15',
            'price' => 1500000,
            'quantity' => 20,
            'status' => 'active',
        ]);

        $order = Order::create([
            'user_id' => $user->id,
            'order_code' => 'SPORT-TEST1234',
            'customer_name' => 'Nguyễn Văn A',
            'customer_phone' => '0901234567',
            'customer_email' => 'customer@test.com',
            'shipping_address' => '123 Lê Lợi, Quận 1, TP. HCM',
            'subtotal' => 1500000,
            'discount_amount' => 0,
            'shipping_fee' => 30000,
            'total_amount' => 1530000,
            'payment_method' => 'cod',
            'payment_status' => 'unpaid',
            'status' => 'shipping',
            'ghn_order_code' => 'GHN12345678',
            'shipping_status' => 'delivering',
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'product_name' => $product->name,
            'price' => 1500000,
            'quantity' => 1,
            'subtotal' => 1500000,
        ]);

        // GET query tracking
        $response = $this->get('/order-tracking?code=SPORT-TEST1234&phone=0901234567');
        $response->assertStatus(200);
        $response->assertSee('SPORT-TEST1234');
        $response->assertSee('Nguyễn Văn A');
        $response->assertSee('GHN12345678');
        $response->assertSee('Giày Đá Bóng Nike Vapor 15');
        $response->assertSee('Tiến Trình Xử Lý Đơn Hàng');

        // POST query tracking
        $postResponse = $this->post('/order-tracking', [
            'order_code' => 'SPORT-TEST1234',
            'phone' => '0901234567',
        ]);
        $postResponse->assertRedirect('/order-tracking?code=SPORT-TEST1234&phone=0901234567');
    }

    public function test_order_tracking_with_invalid_credentials_shows_error(): void
    {
        $response = $this->get('/order-tracking?code=SPORT-NONEXISTENT&phone=0999999999');
        $response->assertStatus(200);
        $response->assertSee('Không Tìm Thấy Đơn Hàng');
    }

    public function test_sitemap_xml_endpoint(): void
    {
        $category = Category::create(['name' => 'Cầu Lông', 'slug' => 'cau-long', 'status' => 'active']);
        $brand = Brand::create(['name' => 'Yonex', 'slug' => 'yonex', 'status' => 'active']);
        Product::create([
            'category_id' => $category->id,
            'brand_id' => $brand->id,
            'name' => 'Vợt Cầu Lông Yonex Astrox 88D',
            'slug' => 'vot-cau-long-yonex-astrox-88d',
            'sku' => 'YONEX-ASTROX-88D',
            'price' => 2800000,
            'quantity' => 15,
            'status' => 'active',
        ]);

        $response = $this->get('/sitemap.xml');
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/xml; charset=utf-8');
        $response->assertSee('<urlset', false);
        $response->assertSee('cau-long');
        $response->assertSee('vot-cau-long-yonex-astrox-88d');
        $response->assertSee('/order-tracking');
    }

    public function test_newsletter_subscription(): void
    {
        // 1. Web form redirect
        $response = $this->post('/newsletter', [
            'email' => 'newsletter_test@example.com',
        ]);
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('contacts', [
            'email' => 'newsletter_test@example.com',
            'subject' => '[Newsletter] Đăng ký nhận thông tin ưu đãi',
        ]);

        // 2. AJAX JSON request
        $ajaxResponse = $this->postJson('/newsletter', [
            'email' => 'ajax_subscriber@example.com',
        ]);
        $ajaxResponse->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);
        $this->assertDatabaseHas('contacts', [
            'email' => 'ajax_subscriber@example.com',
        ]);
    }

    public function test_order_confirmation_mail_renders_content(): void
    {
        Mail::fake();

        $user = User::factory()->create();
        $order = Order::create([
            'user_id' => $user->id,
            'order_code' => 'SPORT-MAIL999',
            'customer_name' => 'Trần Văn Mail',
            'customer_phone' => '0912345678',
            'customer_email' => 'tranvanmail@example.com',
            'shipping_address' => '456 Hai Bà Trưng, Quận 3, TP. HCM',
            'subtotal' => 500000,
            'discount_amount' => 50000,
            'shipping_fee' => 25000,
            'total_amount' => 475000,
            'payment_method' => 'cod',
            'payment_status' => 'unpaid',
            'status' => 'pending',
        ]);

        $mailable = new OrderConfirmationMail($order);
        $mailable->assertHasSubject('[Sport Store] Xác nhận đơn hàng #SPORT-MAIL999 thành công');
        $mailable->assertSeeInHtml('SPORT-MAIL999');
        $mailable->assertSeeInHtml('Trần Văn Mail');
        $mailable->assertSeeInHtml('475.000');
    }
}
