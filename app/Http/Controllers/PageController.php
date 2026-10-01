<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\Coupon;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PageController extends Controller
{
    /**
     * About Us page
     */
    public function about()
    {
        return view('frontend.pages.about');
    }

    /**
     * Contact Us page
     */
    public function contact()
    {
        return view('frontend.pages.contact');
    }

    /**
     * Handle Contact form submission
     */
    public function contactSubmit(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:150',
            'phone' => 'nullable|string|max:20',
            'subject' => 'nullable|string|max:150',
            'message' => 'required|string|min:10|max:2000',
        ], [
            'name.required' => 'Vui lòng nhập họ và tên của bạn.',
            'email.required' => 'Vui lòng cung cấp email liên hệ.',
            'email.email' => 'Địa chỉ email không đúng định dạng.',
            'message.required' => 'Vui lòng nhập nội dung tin nhắn.',
            'message.min' => 'Nội dung tin nhắn tối thiểu 10 ký tự.',
        ]);

        Contact::create($validated);

        return back()->with('success', 'Cảm ơn bạn đã liên hệ! Ban quản trị Sport Store sẽ phản hồi trong thời gian sớm nhất.');
    }

    /**
     * Promotions & Vouchers page
     */
    public function promotions()
    {
        $now = Carbon::now();

        // Active coupons
        $coupons = Coupon::where('status', 'active')
            ->where('start_date', '<=', $now)
            ->where('end_date', '>=', $now)
            ->whereColumn('used', '<', 'quantity')
            ->get();

        // Hot discounted products
        $discountedProducts = Product::with(['category', 'brand'])
            ->where('status', 'active')
            ->whereNotNull('sale_price')
            ->whereColumn('sale_price', '<', 'price')
            ->orderByRaw('((price - sale_price) / price) DESC')
            ->paginate(12);

        return view('frontend.pages.promotions', compact('coupons', 'discountedProducts'));
    }
}
