<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddressRequest;
use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AddressController extends Controller
{
    public function index()
    {
        $addresses = Auth::user()->addresses()->latest()->get();
        return view('frontend.account.addresses', compact('addresses'));
    }

    public function store(AddressRequest $request)
    {
        $user = Auth::user();
        $isDefault = $request->boolean('is_default') || $user->addresses()->count() === 0;

        if ($isDefault) {
            $user->addresses()->update(['is_default' => false]);
        }

        $user->addresses()->create([
            'receiver_name' => $request->receiver_name,
            'phone' => $request->phone,
            'province' => $request->province,
            'district' => $request->district,
            'ward' => $request->ward,
            'address' => $request->address,
            'is_default' => $isDefault,
        ]);

        return back()->with('success', 'Thêm địa chỉ giao hàng mới thành công!');
    }

    public function update(AddressRequest $request, Address $address)
    {
        if ($address->user_id !== Auth::id()) {
            abort(403);
        }

        $user = Auth::user();
        $isDefault = $request->boolean('is_default');

        if ($isDefault) {
            $user->addresses()->where('id', '!=', $address->id)->update(['is_default' => false]);
        }

        $address->update([
            'receiver_name' => $request->receiver_name,
            'phone' => $request->phone,
            'province' => $request->province,
            'district' => $request->district,
            'ward' => $request->ward,
            'address' => $request->address,
            'is_default' => $isDefault || $address->is_default,
        ]);

        return back()->with('success', 'Cập nhật thông tin địa chỉ thành công!');
    }

    public function setDefault(Address $address)
    {
        if ($address->user_id !== Auth::id()) {
            abort(403);
        }

        Auth::user()->addresses()->update(['is_default' => false]);
        $address->update(['is_default' => true]);

        return back()->with('success', 'Đã đặt địa chỉ làm mặc định.');
    }

    public function destroy(Address $address)
    {
        if ($address->user_id !== Auth::id()) {
            abort(403);
        }

        $wasDefault = $address->is_default;
        $address->delete();

        if ($wasDefault) {
            $next = Auth::user()->addresses()->first();
            if ($next) {
                $next->update(['is_default' => true]);
            }
        }

        return back()->with('success', 'Đã xóa địa chỉ thành công.');
    }
}
