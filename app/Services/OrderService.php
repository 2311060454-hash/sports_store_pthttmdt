<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderService
{
    protected CouponService $couponService;

    public function __construct(CouponService $couponService)
    {
        $this->couponService = $couponService;
    }

    /**
     * Create an order from current cart inside a database transaction.
     */
    public function createOrder(array $data, User $user, CartService $cartService): Order
    {
        $cart = $cartService->getCart();
        if (empty($cart)) {
            throw new Exception('Giỏ hàng của bạn đang trống.');
        }

        $order = DB::transaction(function () use ($data, $user, $cartService, $cart) {
            // 1. Verify stock for all items
            foreach ($cart as $item) {
                $product = Product::lockForUpdate()->find($item['product_id']);
                if (!$product || $product->status !== 'active') {
                    throw new Exception('Sản phẩm "' . $item['name'] . '" hiện không còn kinh doanh.');
                }

                if ($item['variant_id']) {
                    $variant = ProductVariant::lockForUpdate()->find($item['variant_id']);
                    if (!$variant || $variant->quantity < $item['quantity']) {
                        $available = $variant ? $variant->quantity : 0;
                        throw new Exception('Sản phẩm "' . $item['name'] . '" (phiên bản ' . $item['variant_text'] . ') chỉ còn ' . $available . ' sản phẩm.');
                    }
                } else {
                    if ($product->quantity < $item['quantity']) {
                        throw new Exception('Sản phẩm "' . $item['name'] . '" chỉ còn ' . $product->quantity . ' sản phẩm trong kho.');
                    }
                }
            }

            // 2. Calculations
            $subtotal = $cartService->getSubtotal();
            $discount = $cartService->getDiscount();
            $shippingFee = isset($data['shipping_fee']) && is_numeric($data['shipping_fee']) 
                ? (float) $data['shipping_fee'] 
                : $cartService->getShippingFee();
            $totalAmount = max(0, $subtotal - $discount + $shippingFee);
            $coupon = $cartService->getCoupon();
            $couponCode = $coupon ? $coupon['code'] : null;

            // 3. Create Order
            $orderCode = 'SPORT-' . strtoupper(Str::random(8));
            $order = Order::create([
                'user_id' => $user->id,
                'order_code' => $orderCode,
                'customer_name' => $data['customer_name'],
                'customer_phone' => $data['customer_phone'],
                'customer_email' => $data['customer_email'],
                'shipping_address' => $data['shipping_address'],
                'to_district_id' => $data['to_district_id'] ?? null,
                'to_ward_code' => $data['to_ward_code'] ?? null,
                'note' => $data['note'] ?? null,
                'subtotal' => $subtotal,
                'discount_amount' => $discount,
                'shipping_fee' => $shippingFee,
                'ghn_total_fee' => (int) $shippingFee,
                'total_amount' => $totalAmount,
                'payment_method' => $data['payment_method'],
                'payment_status' => 'unpaid',
                'status' => 'pending',
                'coupon_code' => $couponCode,
            ]);

            // 4. Create OrderItems & update stock / sold
            foreach ($cart as $item) {
                $product = Product::find($item['product_id']);
                $variant = $item['variant_id'] ? ProductVariant::find($item['variant_id']) : null;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'product_variant_id' => $variant ? $variant->id : null,
                    'product_name' => $item['name'],
                    'product_image' => $item['thumbnail'],
                    'size' => $variant ? $variant->size : null,
                    'color' => $variant ? $variant->color : null,
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'subtotal' => $item['subtotal'],
                ]);

                // Reduce product stock & increase sold
                $product->decrement('quantity', $item['quantity']);
                $product->increment('sold', $item['quantity']);

                // Reduce variant stock
                if ($variant) {
                    $variant->decrement('quantity', $item['quantity']);
                }
            }

            // 5. Increment coupon usage
            if ($couponCode) {
                $this->couponService->incrementUsage($couponCode);
            }

            // 6. Clear cart
            $cartService->clear();

            return $order;
        });

        // 7. Auto Push Order to GHN Shipping Service
        try {
            $order->load(['items.product']);
            app(\App\Services\GHNOrderService::class)->create($order);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::warning('Auto Push to GHN error: ' . $e->getMessage());
        }

        return $order->fresh();
    }

    /**
     * Cancel an order if it is in pending status.
     */
    public function cancelOrder(Order $order, string $reason, User $user): Order
    {
        if ($order->user_id !== $user->id && !$user->isAdmin()) {
            throw new Exception('Bạn không có quyền thao tác trên đơn hàng này.');
        }

        if (!$order->canBeCancelled()) {
            throw new Exception('Đơn hàng đã được xử lý hoặc hoàn thành, không thể hủy.');
        }

        return DB::transaction(function () use ($order, $reason) {
            $order->update([
                'status' => 'cancelled',
                'cancel_reason' => $reason,
            ]);

            // Restore product inventory and revert sold count
            foreach ($order->items as $item) {
                if ($item->product_id) {
                    $product = Product::find($item->product_id);
                    if ($product) {
                        $product->increment('quantity', $item->quantity);
                        $newSold = max(0, $product->sold - $item->quantity);
                        $product->update(['sold' => $newSold]);
                    }
                }

                if ($item->product_variant_id) {
                    $variant = ProductVariant::find($item->product_variant_id);
                    if ($variant) {
                        $variant->increment('quantity', $item->quantity);
                    }
                }
            }

            return $order;
        });
    }

    /**
     * Admin updates order status strictly along allowable progression.
     */
    public function updateStatus(Order $order, string $newStatus): Order
    {
        $validStatuses = ['pending', 'confirmed', 'preparing', 'shipping', 'completed', 'cancelled'];
        if (!in_array($newStatus, $validStatuses)) {
            throw new Exception('Trạng thái đơn hàng không hợp lệ.');
        }

        if ($order->status === 'cancelled') {
            throw new Exception('Đơn hàng đã bị hủy, không thể thay đổi trạng thái.');
        }

        if ($order->status === 'completed' && $newStatus !== 'completed') {
            throw new Exception('Đơn hàng đã hoàn thành, không thể hoàn tác trạng thái.');
        }

        // Handle cancellation from admin
        if ($newStatus === 'cancelled') {
            return DB::transaction(function () use ($order) {
                $order->update([
                    'status' => 'cancelled',
                    'cancel_reason' => 'Quản trị viên hủy đơn hàng.',
                ]);

                // Restore stock
                foreach ($order->items as $item) {
                    if ($item->product_id) {
                        $product = Product::find($item->product_id);
                        if ($product) {
                            $product->increment('quantity', $item->quantity);
                            $newSold = max(0, $product->sold - $item->quantity);
                            $product->update(['sold' => $newSold]);
                        }
                    }

                    if ($item->product_variant_id) {
                        $variant = ProductVariant::find($item->product_variant_id);
                        if ($variant) {
                            $variant->increment('quantity', $item->quantity);
                        }
                    }
                }
                return $order;
            });
        }

        $order->update([
            'status' => $newStatus,
            'payment_status' => ($newStatus === 'completed' && $order->payment_method === 'cod') ? 'paid' : $order->payment_status,
        ]);

        return $order;
    }
}
