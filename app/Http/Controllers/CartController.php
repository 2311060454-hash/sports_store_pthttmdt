<?php

namespace App\Http\Controllers;

use App\Services\CartService;
use Exception;
use Illuminate\Http\Request;

class CartController extends Controller
{
    protected CartService $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function index()
    {
        $cart = $this->cartService->getCart();
        $subtotal = $this->cartService->getSubtotal();
        $discount = $this->cartService->getDiscount();
        $shippingFee = $this->cartService->getShippingFee();
        $total = $this->cartService->getTotal();
        $coupon = $this->cartService->getCoupon();

        return view('frontend.cart.index', compact('cart', 'subtotal', 'discount', 'shippingFee', 'total', 'coupon'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer|exists:products,id',
            'quantity' => 'nullable|integer|min:1',
            'variant_id' => 'nullable|integer|exists:product_variants,id',
        ]);

        $productId = (int) $request->input('product_id');
        $quantity = (int) ($request->input('quantity', 1));
        $variantId = $request->input('variant_id') ? (int) $request->input('variant_id') : null;

        try {
            $this->cartService->add($productId, $quantity, $variantId);

            if ($request->boolean('buy_now')) {
                return redirect()->route('checkout')->with('success', 'Đã thêm sản phẩm vào đơn hàng.');
            }

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Đã thêm sản phẩm vào giỏ hàng thành công!',
                    'cart_count' => $this->cartService->getItemsCount(),
                    'total' => number_format($this->cartService->getTotal(), 0, ',', '.') . ' ₫',
                ]);
            }

            return back()->with('success', 'Đã thêm sản phẩm vào giỏ hàng thành công!');
        } catch (Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage(),
                ], 422);
            }

            return back()->with('error', $e->getMessage());
        }
    }

    public function update(Request $request)
    {
        $request->validate([
            'row_id' => 'required|string',
            'quantity' => 'required|integer|min:1',
        ]);

        try {
            $this->cartService->update($request->input('row_id'), (int) $request->input('quantity'));

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Cập nhật số lượng thành công!',
                    'cart_count' => $this->cartService->getItemsCount(),
                    'subtotal' => number_format($this->cartService->getSubtotal(), 0, ',', '.') . ' ₫',
                    'discount' => number_format($this->cartService->getDiscount(), 0, ',', '.') . ' ₫',
                    'shipping_fee' => number_format($this->cartService->getShippingFee(), 0, ',', '.') . ' ₫',
                    'total' => number_format($this->cartService->getTotal(), 0, ',', '.') . ' ₫',
                ]);
            }

            return back()->with('success', 'Cập nhật giỏ hàng thành công!');
        } catch (Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage(),
                ], 422);
            }

            return back()->with('error', $e->getMessage());
        }
    }

    public function remove(Request $request)
    {
        $request->validate([
            'row_id' => 'required|string',
        ]);

        $this->cartService->remove($request->input('row_id'));

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Đã xóa sản phẩm khỏi giỏ hàng.',
                'cart_count' => $this->cartService->getItemsCount(),
                'total' => number_format($this->cartService->getTotal(), 0, ',', '.') . ' ₫',
            ]);
        }

        return back()->with('success', 'Đã xóa sản phẩm khỏi giỏ hàng.');
    }

    public function clear()
    {
        $this->cartService->clear();
        return redirect()->route('cart.index')->with('success', 'Đã làm trống giỏ hàng.');
    }
}
