<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $customers = User::where('role', 'customer')->get();
        $products = Product::all();

        if ($customers->isEmpty() || $products->isEmpty() || Order::count() >= 10) {
            return;
        }

        $statuses = ['completed', 'completed', 'completed', 'shipping', 'preparing', 'confirmed', 'pending', 'cancelled'];
        $comments = [
            'Sản phẩm rất đẹp, chất lượng vượt mong đợi, giao hàng nhanh chóng!',
            'Đóng gói cẩn thận, hàng chính hãng đúng như mô tả. Sẽ tiếp tục ủng hộ shop.',
            'Đi rất êm chân, độ bám sân tốt, đá 2 trận rồi thấy rất ổn.',
            'Vợt đánh đầm tay, trợ lực tốt, cước căng chuẩn kg yêu cầu.',
            'Màu sắc bên ngoài đẹp hơn trong ảnh, chất liệu bền bỉ và thoáng khí.'
        ];

        foreach ($customers as $index => $customer) {
            // Create 1-2 orders for each customer
            $orderCount = rand(1, 2);
            for ($k = 0; $k < $orderCount; $k++) {
                $status = $statuses[($index + $k) % count($statuses)];
                $randomDays = rand(1, 30);
                $createdAt = Carbon::now()->subDays($randomDays);

                $selectedProducts = $products->random(rand(1, 3));
                $subtotal = 0;

                $orderCode = 'SPORT-' . strtoupper(Str::random(8));
                $order = Order::create([
                    'user_id' => $customer->id,
                    'order_code' => $orderCode,
                    'customer_name' => $customer->name,
                    'customer_phone' => $customer->phone ?? '0901234567',
                    'customer_email' => $customer->email,
                    'shipping_address' => 'Địa chỉ nhận hàng của ' . $customer->name . ', Việt Nam',
                    'subtotal' => 0,
                    'discount_amount' => 0,
                    'shipping_fee' => 0,
                    'total_amount' => 0,
                    'payment_method' => ($index % 2 === 0) ? 'cod' : 'bank_transfer',
                    'payment_status' => ($status === 'completed') ? 'paid' : 'unpaid',
                    'status' => $status,
                    'cancel_reason' => ($status === 'cancelled') ? 'Khách hàng đổi ý muốn chọn size khác.' : null,
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt,
                ]);

                foreach ($selectedProducts as $prod) {
                    $qty = rand(1, 2);
                    $price = $prod->final_price;
                    $itemSubtotal = $price * $qty;
                    $subtotal += $itemSubtotal;

                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $prod->id,
                        'product_name' => $prod->name,
                        'product_image' => $prod->thumbnail,
                        'price' => $price,
                        'quantity' => $qty,
                        'subtotal' => $itemSubtotal,
                        'created_at' => $createdAt,
                        'updated_at' => $createdAt,
                    ]);

                    // If order is completed, randomly create a review
                    if ($status === 'completed' && rand(0, 1) === 1) {
                        Review::firstOrCreate(
                            [
                                'user_id' => $customer->id,
                                'product_id' => $prod->id,
                                'order_id' => $order->id,
                            ],
                            [
                                'rating' => rand(4, 5),
                                'comment' => $comments[array_rand($comments)],
                                'status' => 'active',
                                'created_at' => $createdAt->copy()->addDays(2),
                            ]
                        );
                    }
                }

                $shippingFee = $subtotal >= 500000 ? 0 : 30000;
                $order->update([
                    'subtotal' => $subtotal,
                    'shipping_fee' => $shippingFee,
                    'total_amount' => $subtotal + $shippingFee,
                ]);
            }
        }
    }
}
