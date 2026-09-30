<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        $banners = Banner::where('status', 'active')->latest()->take(5)->get();

        $featuredCategories = Category::where('status', 'active')
            ->withCount(['products' => function ($q) {
                $q->where('status', 'active');
            }])
            ->take(8)
            ->get();

        $featuredProducts = Product::with(['category', 'brand', 'reviews'])
            ->where('status', 'active')
            ->where('featured', true)
            ->latest()
            ->take(8)
            ->get();

        $newProducts = Product::with(['category', 'brand', 'reviews'])
            ->where('status', 'active')
            ->latest()
            ->take(8)
            ->get();

        $bestSellers = Product::with(['category', 'brand', 'reviews'])
            ->where('status', 'active')
            ->orderBy('sold', 'desc')
            ->take(8)
            ->get();

        $onSaleProducts = Product::with(['category', 'brand', 'reviews'])
            ->where('status', 'active')
            ->whereNotNull('sale_price')
            ->whereColumn('sale_price', '<', 'price')
            ->latest()
            ->take(8)
            ->get();

        $brands = Brand::where('status', 'active')->take(8)->get();

        return view('frontend.home', compact(
            'banners',
            'featuredCategories',
            'featuredProducts',
            'newProducts',
            'bestSellers',
            'onSaleProducts',
            'brands'
        ));
    }
}
