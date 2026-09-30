<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;

class AdminReviewController extends Controller
{
    public function index(Request $request)
    {
        $query = Review::with(['user', 'product', 'order']);

        if ($request->filled('rating')) {
            $query->where('rating', $request->rating);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $reviews = $query->latest()->paginate(15)->withQueryString();

        return view('admin.reviews.index', compact('reviews'));
    }

    public function toggleStatus(Review $review)
    {
        $newStatus = $review->status === 'active' ? 'hidden' : 'active';
        $review->update(['status' => $newStatus]);

        $msg = $newStatus === 'active' ? 'Đã hiển thị đánh giá.' : 'Đã ẩn đánh giá khỏi website.';
        return back()->with('success', $msg);
    }

    public function destroy(Review $review)
    {
        $review->delete();
        return back()->with('success', 'Xóa đánh giá thành công!');
    }
}
