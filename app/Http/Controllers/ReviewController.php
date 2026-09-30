<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReviewRequest;
use App\Models\Order;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(ReviewRequest $request)
    {
        $userId = Auth::id();
        $orderId = (int) $request->order_id;
        $productId = (int) $request->product_id;

        // Check if order exists, belongs to user, and is completed
        $order = Order::where('id', $orderId)
            ->where('user_id', $userId)
            ->where('status', 'completed')
            ->first();

        if (!$order) {
            return back()->with('error', 'Bạn chỉ có thể đánh giá sản phẩm sau khi đơn hàng đã hoàn thành.');
        }

        // Check if product was purchased in this order
        $hasProduct = $order->items()->where('product_id', $productId)->exists();
        if (!$hasProduct) {
            return back()->with('error', 'Sản phẩm này không nằm trong đơn hàng được chọn.');
        }

        // Check if already reviewed for this order
        $exists = Review::where('user_id', $userId)
            ->where('product_id', $productId)
            ->where('order_id', $orderId)
            ->exists();

        if ($exists) {
            return back()->with('error', 'Bạn đã gửi đánh giá cho sản phẩm này trong đơn hàng rồi.');
        }

        Review::create([
            'user_id' => $userId,
            'product_id' => $productId,
            'order_id' => $orderId,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'status' => 'active',
        ]);

        return back()->with('success', 'Cảm ơn bạn đã gửi đánh giá! Ý kiến của bạn giúp chúng tôi cải thiện chất lượng phục vụ.');
    }
}
