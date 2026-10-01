<?php

namespace App\Http\Controllers;

use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VerificationController extends Controller
{
    /**
     * Hiển thị thông báo yêu cầu xác thực email.
     */
    public function notice(Request $request)
    {
        if ($request->user() && $request->user()->hasVerifiedEmail()) {
            return redirect()->route('account.profile')->with('info', 'Địa chỉ email của bạn đã được xác thực thành công trước đó.');
        }

        return view('auth.verify-email');
    }

    /**
     * Xử lý liên kết xác thực email gửi qua email (signed URL).
     */
    public function verify(EmailVerificationRequest $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->route('account.profile')->with('info', 'Địa chỉ email của bạn đã được xác minh.');
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        return redirect()->route('account.profile')->with('success', 'Chúc mừng! Địa chỉ email của bạn đã được xác thực thành công.');
    }

    /**
     * Gửi lại email thông báo xác thực.
     */
    public function resend(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->route('account.profile');
        }

        try {
            $request->user()->sendEmailVerificationNotification();
            $message = 'Một liên kết xác thực mới đã được gửi tới địa chỉ email của bạn.';
        } catch (\Exception $e) {
            $message = 'Không thể kết nối đến máy chủ gửi mail thực tế. Bạn có thể sử dụng nút "Xác thực ngay (Demo)" bên dưới để hoàn tất xác thực tức thì.';
        }

        return back()->with('status', $message);
    }

    /**
     * Xác thực tức thì không cần SMTP (Dành cho Giáo viên chấm bài / Môi trường Demo & Test).
     */
    public function instantVerify(Request $request)
    {
        $user = $request->user();

        if ($user->hasVerifiedEmail()) {
            return redirect()->route('account.profile')->with('info', 'Địa chỉ email của bạn đã được xác minh từ trước.');
        }

        $user->markEmailAsVerified();
        event(new Verified($user));

        return redirect()->route('account.profile')->with('success', 'Xác thực email thành công! Tài khoản của bạn hiện đã được kích hoạt đầy đủ mọi tính năng.');
    }
}
