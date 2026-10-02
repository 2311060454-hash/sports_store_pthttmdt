<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\ProfileUpdateRequest;
use App\Services\ImageService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AccountController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $recentOrders = $user->orders()->latest()->take(5)->get();
        $totalOrdersCount = $user->orders()->count();
        $wishlistCount = $user->wishlists()->count();

        // Loyalty & Rewards calculation (Chương trình khách hàng thân thiết)
        $totalSpent = (float) $user->orders()
            ->where(function ($q) {
                $q->where('payment_status', 'paid')
                  ->orWhereIn('status', ['completed', 'shipping', 'delivered']);
            })
            ->sum('total_amount');

        $loyaltyPoints = (int) floor($totalSpent / 10000);

        [$tierName, $tierColor, $tierNext, $tierProgress] = match (true) {
            $loyaltyPoints >= 5000 => ['Kim Cương', 'info', 5000, 100],
            $loyaltyPoints >= 2000 => ['Vàng', 'warning', 5000, min(100, (int)(($loyaltyPoints - 2000) / 3000 * 100))],
            $loyaltyPoints >= 500  => ['Bạc', 'secondary', 2000, min(100, (int)(($loyaltyPoints - 500) / 1500 * 100))],
            default                => ['Đồng', 'dark', 500, min(100, (int)($loyaltyPoints / 500 * 100))],
        };

        return view('frontend.account.index', compact(
            'user', 'recentOrders', 'totalOrdersCount', 'wishlistCount',
            'totalSpent', 'loyaltyPoints', 'tierName', 'tierColor', 'tierNext', 'tierProgress'
        ));
    }

    public function profile()
    {
        $user = Auth::user();
        return view('frontend.account.profile', compact('user'));
    }

    public function updateProfile(ProfileUpdateRequest $request, ImageService $imageService)
    {
        $user = Auth::user();
        $data = $request->only(['name', 'phone']);

        if ($request->hasFile('avatar')) {
            $imageService->delete($user->avatar);
            $data['avatar'] = $imageService->upload($request->file('avatar'), 'avatars');
        }

        $user->update($data);

        return back()->with('success', 'Cập nhật thông tin tài khoản thành công!');
    }

    public function changePassword()
    {
        return view('frontend.account.change-password');
    }

    public function updatePassword(ChangePasswordRequest $request)
    {
        $user = Auth::user();

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Đổi mật khẩu thành công! Hãy lưu lại mật khẩu mới.');
    }
}
