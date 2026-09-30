<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để truy cập trang quản trị.');
        }

        if (auth()->user()->role !== 'admin' || auth()->user()->status !== 'active') {
            abort(403, 'Bạn không có quyền truy cập vào trang quản trị hệ thống.');
        }

        return $next($request);
    }
}
