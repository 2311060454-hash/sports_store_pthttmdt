<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductVariant;
use Exception;
use Illuminate\Support\Facades\Session;

class CartService
{
    protected string $sessionKey = 'shopping_cart';
    protected string $couponKey = 'applied_coupon';
    protected CouponService $couponService;

    public function __construct(CouponService $couponService)
    {
        $this->couponService = $couponService;
    }

    public function getCart(): array
    {
        return Session::get($this->sessionKey, []);
    }

    public function getItemsCount(): int
    {
        $cart = $this->getCart();
        $count = 0;
        foreach ($cart as $item) {
            $count += $item['quantity'];
        }
        return $count;
    }

    public function add(int $productId, int $quantity = 1, ?int $variantId = null): array
    {
        $product = Product::with(['variants'])->findOrFail($productId);

        if ($product->status !== 'active') {
            throw new Exception('Sản phẩm này hiện đang ngừng kinh doanh.');
        }

        if ($product->quantity <= 0) {
            throw new Exception('Sản phẩm "' . $product->name . '" hiện đã hết hàng.');
        }

        $variant = null;
        $variantText = '';
        $unitPrice = $product->final_price;

        if ($variantId) {
            $variant = ProductVariant::where('product_id', $productId)->find($variantId);
            if ($variant) {
                if ($variant->quantity <= 0) {
                    throw new Exception('Phiên bản được chọn hiện đã hết hàng.');
                }
                $unitPrice += $variant->additional_price;
                $variantParts = array_filter([$variant->size ? 'Size: ' . $variant->size : null, $variant->color ? 'Màu: ' . $variant->color : null]);
                $variantText = implode(', ', $variantParts);
            }
        }

        $cart = $this->getCart();
        $rowId = 'item_' . $productId . '_' . ($variantId ?? '0');

        $currentQtyInCart = isset($cart[$rowId]) ? $cart[$rowId]['quantity'] : 0;
        $newQty = $currentQtyInCart + $quantity;

        // Stock check
        $maxStock = $variant ? $variant->quantity : $product->quantity;
        if ($newQty > $maxStock) {
            throw new Exception('Số lượng yêu cầu (' . $newQty . ') vượt quá số lượng tồn kho còn lại (' . $maxStock . ').');
        }

        $cart[$rowId] = [
            'row_id' => $rowId,
            'product_id' => $product->id,
            'variant_id' => $variantId,
            'name' => $product->name,
            'slug' => $product->slug,
            'thumbnail' => $product->thumbnail,
            'price' => $unitPrice,
            'quantity' => $newQty,
            'variant_text' => $variantText,
            'subtotal' => $unitPrice * $newQty,
            'max_stock' => $maxStock,
        ];

        Session::put($this->sessionKey, $cart);

        // Revalidate coupon if applied
        $this->refreshCoupon();

        return $cart[$rowId];
    }

    public function update(string $rowId, int $quantity): array
    {
        $cart = $this->getCart();

        if (!isset($cart[$rowId])) {
            throw new Exception('Sản phẩm không tồn tại trong giỏ hàng.');
        }

        if ($quantity <= 0) {
            return $this->remove($rowId);
        }

        $item = $cart[$rowId];
        $product = Product::find($item['product_id']);
        if (!$product || $product->status !== 'active') {
            throw new Exception('Sản phẩm không còn khả dụng.');
        }

        $maxStock = $item['max_stock'];
        if ($item['variant_id']) {
            $variant = ProductVariant::find($item['variant_id']);
            if ($variant) {
                $maxStock = $variant->quantity;
            }
        } else {
            $maxStock = $product->quantity;
        }

        if ($quantity > $maxStock) {
            throw new Exception('Số lượng yêu cầu vượt quá tồn kho (Tối đa: ' . $maxStock . ').');
        }

        $cart[$rowId]['quantity'] = $quantity;
        $cart[$rowId]['subtotal'] = $cart[$rowId]['price'] * $quantity;
        $cart[$rowId]['max_stock'] = $maxStock;

        Session::put($this->sessionKey, $cart);
        $this->refreshCoupon();

        return $cart;
    }

    public function remove(string $rowId): array
    {
        $cart = $this->getCart();
        if (isset($cart[$rowId])) {
            unset($cart[$rowId]);
            Session::put($this->sessionKey, $cart);
        }

        $this->refreshCoupon();
        return $cart;
    }

    public function clear(): void
    {
        Session::forget($this->sessionKey);
        Session::forget($this->couponKey);
    }

    public function getSubtotal(): float
    {
        $cart = $this->getCart();
        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['subtotal'];
        }
        return (float) $subtotal;
    }

    public function getShippingFee(): float
    {
        $subtotal = $this->getSubtotal();
        if ($subtotal <= 0) {
            return 0.0;
        }

        // Miễn phí vận chuyển cho đơn từ 500.000đ trở lên
        return $subtotal >= 500000 ? 0.0 : 30000.0;
    }

    public function applyCoupon(string $code): array
    {
        $subtotal = $this->getSubtotal();
        if ($subtotal <= 0) {
            return ['success' => false, 'message' => 'Giỏ hàng đang trống, không thể áp dụng mã giảm giá.'];
        }

        $res = $this->couponService->validate($code, $subtotal);
        if (!$res['valid']) {
            $this->removeCoupon();
            return ['success' => false, 'message' => $res['message']];
        }

        Session::put($this->couponKey, [
            'code' => $res['coupon']->code,
            'type' => $res['coupon']->type,
            'value' => $res['coupon']->value,
            'discount' => $res['discount'],
        ]);

        return ['success' => true, 'message' => $res['message'], 'discount' => $res['discount']];
    }

    public function removeCoupon(): void
    {
        Session::forget($this->couponKey);
    }

    public function getCoupon(): ?array
    {
        return Session::get($this->couponKey);
    }

    public function getDiscount(): float
    {
        $coupon = $this->getCoupon();
        return $coupon ? (float) $coupon['discount'] : 0.0;
    }

    public function getTotal(): float
    {
        $subtotal = $this->getSubtotal();
        if ($subtotal <= 0) {
            return 0.0;
        }
        $discount = $this->getDiscount();
        $shipping = $this->getShippingFee();

        $total = ($subtotal - $discount) + $shipping;
        return max(0.0, (float) $total);
    }

    protected function refreshCoupon(): void
    {
        $coupon = $this->getCoupon();
        if ($coupon) {
            $subtotal = $this->getSubtotal();
            if ($subtotal <= 0) {
                $this->removeCoupon();
                return;
            }
            $res = $this->couponService->validate($coupon['code'], $subtotal);
            if ($res['valid']) {
                Session::put($this->couponKey, [
                    'code' => $coupon['code'],
                    'type' => $coupon['type'],
                    'value' => $coupon['value'],
                    'discount' => $res['discount'],
                ]);
            } else {
                $this->removeCoupon();
            }
        }
    }
}
