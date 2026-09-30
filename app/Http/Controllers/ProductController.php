<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Review;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    protected ProductService $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function index(Request $request)
    {
        $products = $this->productService->getFilteredProducts($request->all(), 12);

        $categories = Category::where('status', 'active')
            ->withCount(['products' => function ($q) {
                $q->where('status', 'active');
            }])
            ->get();

        $brands = Brand::where('status', 'active')
            ->withCount(['products' => function ($q) {
                $q->where('status', 'active');
            }])
            ->get();

        $availableSizes = ProductVariant::whereNotNull('size')
            ->where('size', '!=', '')
            ->distinct()
            ->pluck('size');

        $availableColors = ProductVariant::whereNotNull('color')
            ->where('color', '!=', '')
            ->distinct()
            ->pluck('color');

        return view('frontend.products.index', compact(
            'products',
            'categories',
            'brands',
            'availableSizes',
            'availableColors'
        ));
    }

    public function show(string $slug)
    {
        $product = Product::with([
            'category',
            'brand',
            'images',
            'variants',
            'approvedReviews.user'
        ])
            ->where('slug', $slug)
            ->where('status', 'active')
            ->firstOrFail();

        // Increment view count
        $product->increment('views');

        // Related products in same category
        $relatedProducts = Product::with(['category', 'brand', 'reviews'])
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('status', 'active')
            ->take(4)
            ->get();

        // Check if user is eligible to review: bought in completed order and not reviewed yet for that order
        $canReview = false;
        $eligibleOrderId = null;

        if (Auth::check()) {
            $userId = Auth::id();
            $completedOrder = Order::where('user_id', $userId)
                ->where('status', 'completed')
                ->whereHas('items', function ($q) use ($product) {
                    $q->where('product_id', $product->id);
                })
                ->whereDoesntHave('reviews', function ($q) use ($product, $userId) {
                    $q->where('product_id', $product->id)->where('user_id', $userId);
                })
                ->latest()
                ->first();

            if ($completedOrder) {
                $canReview = true;
                $eligibleOrderId = $completedOrder->id;
            }
        }

        return view('frontend.products.show', compact(
            'product',
            'relatedProducts',
            'canReview',
            'eligibleOrderId'
        ));
    }

    public function search(Request $request)
    {
        return $this->index($request);
    }
}
