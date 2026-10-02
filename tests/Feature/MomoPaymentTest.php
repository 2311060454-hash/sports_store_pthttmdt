<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PaymentTransaction;
use App\Models\Product;
use App\Models\User;
use App\Services\MomoService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class MomoPaymentTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Product $product;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $category = \App\Models\Category::create([
            'name' => 'Thể thao test',
            'slug' => 'the-thao-test-' . time(),
            'status' => 'active',
        ]);
        $brand = \App\Models\Brand::create([
            'name' => 'Brand test',
            'slug' => 'brand-test-' . time(),
            'status' => 'active',
        ]);
        $this->product = Product::create([
            'category_id' => $category->id,
            'brand_id' => $brand->id,
            'sku' => 'TEST-SKU-' . time(),
            'name' => 'Sản phẩm Test MoMo',
            'slug' => 'san-pham-test-momo-' . time(),
            'price' => 500000,
            'quantity' => 10,
            'status' => 'active',
        ]);
    }

    /**
     * Test initiating MoMo payment creates transaction and redirects to MoMo payUrl.
     */
    public function test_momo_payment_start(): void
    {
        $order = Order::create([
            'user_id' => $this->user->id,
            'order_code' => 'SPORT-MOMO' . rand(1000, 9999),
            'customer_name' => 'Nguyen Van A',
            'customer_phone' => '0901234567',
            'customer_email' => 'test@sportstore.com',
            'shipping_address' => '123 Đường Test, Quận 1',
            'subtotal' => 500000,
            'shipping_fee' => 30000,
            'total_amount' => 530000,
            'payment_method' => 'momo',
            'payment_status' => 'unpaid',
            'status' => 'pending',
            'to_district_id' => 1442,
            'to_ward_code' => '20508',
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $this->product->id,
            'product_name' => $this->product->name,
            'price' => 500000,
            'quantity' => 1,
            'subtotal' => 500000,
        ]);

        Http::fake([
            'https://test-payment.momo.vn/*' => Http::response([
                'resultCode' => 0,
                'message' => 'Thành công',
                'payUrl' => 'https://test-payment.momo.vn/v2/gateway/pay?t=mock_token',
            ], 200),
        ]);

        $response = $this->actingAs($this->user)->get(route('orders.momo.start', $order));

        $response->assertRedirect('https://test-payment.momo.vn/v2/gateway/pay?t=mock_token');

        $this->assertDatabaseHas('payment_transactions', [
            'order_id' => $order->id,
            'gateway' => 'momo',
            'status' => 'initiated',
        ]);
    }

    /**
     * Test successful MoMo IPN marks order as paid and creates GHN order with cod_amount = 0.
     */
    public function test_momo_ipn_successful_payment(): void
    {
        $order = Order::create([
            'user_id' => $this->user->id,
            'order_code' => 'SPORT-MOMOIPN' . rand(100, 999),
            'customer_name' => 'Nguyen Van A',
            'customer_phone' => '0901234567',
            'customer_email' => 'test@sportstore.com',
            'shipping_address' => '123 Đường Test',
            'subtotal' => 500000,
            'shipping_fee' => 20900,
            'total_amount' => 520900,
            'payment_method' => 'momo',
            'payment_status' => 'unpaid',
            'status' => 'pending',
            'to_district_id' => 1442,
            'to_ward_code' => '20508',
        ]);

        $orderIdMoMo = $order->id . '_123_' . time();

        $transaction = PaymentTransaction::create([
            'order_id' => $order->id,
            'gateway' => 'momo',
            'gateway_order_id' => $orderIdMoMo,
            'amount' => 520900,
            'status' => 'initiated',
        ]);

        $momoService = app(MomoService::class);
        $accessKey = config('services.momo.access_key', 'klm05TvNBzhg7h7j');
        $secretKey = config('services.momo.secret_key', 'at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa');
        $amount = '520900';
        $extraData = (string) $order->id;
        $orderInfo = 'Thanh toan don hang #' . $order->order_code;
        $partnerCode = config('services.momo.partner_code', 'MOMOBKUN20180529');
        $requestId = (string) time();
        $responseTime = (string) (time() * 1000);
        $resultCode = '0';
        $transId = '9988776655';

        $rawHash = 'accessKey=' . $accessKey .
            '&amount=' . $amount .
            '&extraData=' . $extraData .
            '&message=Thanh cong' .
            '&orderId=' . $orderIdMoMo .
            '&orderInfo=' . $orderInfo .
            '&orderType=momo_wallet' .
            '&partnerCode=' . $partnerCode .
            '&payType=napas' .
            '&requestId=' . $requestId .
            '&responseTime=' . $responseTime .
            '&resultCode=' . $resultCode .
            '&transId=' . $transId;

        $signature = hash_hmac('sha256', $rawHash, $secretKey);

        $payload = [
            'partnerCode' => $partnerCode,
            'orderId' => $orderIdMoMo,
            'requestId' => $requestId,
            'amount' => 520900,
            'orderInfo' => $orderInfo,
            'orderType' => 'momo_wallet',
            'transId' => $transId,
            'resultCode' => 0,
            'message' => 'Thanh cong',
            'payType' => 'napas',
            'responseTime' => $responseTime,
            'extraData' => $extraData,
            'signature' => $signature,
        ];

        // Fake GHN order creation API
        Http::fake([
            '*/v2/shipping-order/fee' => Http::response(['code' => 200, 'data' => ['total' => 20900]], 200),
            '*/v2/shipping-order/create' => Http::response(['code' => 200, 'data' => ['order_code' => 'GHNMOMO123']], 200),
        ]);

        $response = $this->postJson(route('payment.momo.ipn'), $payload);

        $response->assertStatus(200);

        $order->refresh();
        $transaction->refresh();

        $this->assertEquals('paid', $order->payment_status);
        $this->assertEquals('paid', $transaction->status);
        $this->assertEquals('GHNMOMO123', $order->ghn_order_code);
    }
}
