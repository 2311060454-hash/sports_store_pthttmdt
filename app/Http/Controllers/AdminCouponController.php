<?php

namespace App\Http\Controllers;

use App\Http\Requests\CouponRequest;
use App\Models\Coupon;
use Illuminate\Http\Request;

class AdminCouponController extends Controller
{
    public function index(Request $request)
    {
        $query = Coupon::query();

        if ($request->filled('q')) {
            $query->where('code', 'like', '%' . trim($request->q) . '%');
        }

        $coupons = $query->latest()->paginate(10)->withQueryString();
        return view('admin.coupons.index', compact('coupons'));
    }

    public function create()
    {
        return view('admin.coupons.create');
    }

    public function store(CouponRequest $request)
    {
        $data = $request->validated();
        $data['code'] = strtoupper(trim($data['code']));
        $data['used'] = 0;

        Coupon::create($data);

        return redirect()->route('admin.coupons.index')->with('success', 'Tạo mã giảm giá mới thành công!');
    }

    public function edit(Coupon $coupon)
    {
        return view('admin.coupons.edit', compact('coupon'));
    }

    public function update(CouponRequest $request, Coupon $coupon)
    {
        $data = $request->validated();
        $data['code'] = strtoupper(trim($data['code']));

        $coupon->update($data);

        return redirect()->route('admin.coupons.index')->with('success', 'Cập nhật mã giảm giá thành công!');
    }

    public function destroy(Coupon $coupon)
    {
        $coupon->delete();
        return redirect()->route('admin.coupons.index')->with('success', 'Xóa mã giảm giá thành công!');
    }
}
