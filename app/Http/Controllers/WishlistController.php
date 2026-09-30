<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function index()
    {
        $wishlists = Auth::user()->wishlists()->with('product.category')->latest()->paginate(12);
        return view('frontend.account.wishlist', compact('wishlists'));
    }

    public function toggle(Request $request)
    {
        $request->validate(['product_id' => 'required|exists:products,id']);

        $userId = Auth::id();
        $productId = (int) $request->input('product_id');

        $exists = Wishlist::where('user_id', $userId)->where('product_id', $productId)->first();

        if ($exists) {
            $exists->delete();
            $added = false;
            $msg = 'Đã xóa sản phẩm khỏi danh sách yêu thích.';
        } else {
            Wishlist::create(['user_id' => $userId, 'product_id' => $productId]);
            $added = true;
            $msg = 'Đã thêm sản phẩm vào danh sách yêu thích!';
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'added' => $added,
                'message' => $msg,
                'wishlist_count' => Wishlist::where('user_id', $userId)->count(),
            ]);
        }

        return back()->with('success', $msg);
    }
}
