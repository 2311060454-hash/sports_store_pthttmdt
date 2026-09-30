<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('home');
        }
        return view('auth.login');
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');
        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $user = Auth::user();

            if ($user->status !== 'active') {
                Auth::logout();
                return back()->withInput($request->only('email', 'remember'))
                    ->withErrors(['email' => 'Tài khoản của bạn đã bị khóa hoặc ngừng hoạt động. Vui lòng liên hệ hỗ trợ.']);
            }

            $request->session()->regenerate();

            if ($user->isAdmin()) {
                return redirect()->intended(route('admin.dashboard'))->with('success', 'Đăng nhập trang quản trị thành công!');
            }

            return redirect()->intended(route('home'))->with('success', 'Chào mừng bạn quay trở lại, ' . $user->name . '!');
        }

        return back()->withInput($request->only('email', 'remember'))
            ->withErrors(['email' => 'Email hoặc mật khẩu không chính xác.']);
    }

    public function showRegisterForm()
    {
        if (Auth::check()) {
            return redirect()->route('home');
        }
        return view('auth.register');
    }

    public function register(RegisterRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role' => 'customer',
            'status' => 'active',
        ]);

        Auth::login($user);

        return redirect()->route('home')->with('success', 'Đăng ký tài khoản thành công! Chúc bạn có trải nghiệm mua sắm tuyệt vời tại Sport Store.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Bạn đã đăng xuất an toàn khỏi hệ thống.');
    }
}
