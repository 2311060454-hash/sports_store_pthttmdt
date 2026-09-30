@extends('layouts.app')

@section('title', 'Đăng Nhập Tài Khoản - Sport Store')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body p-4 p-md-5">
                    <div class="text-center mb-4">
                        <div class="bg-primary-subtle text-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width: 60px; height: 60px;">
                            <i class="fa-solid fa-user-lock fs-3 text-warning"></i>
                        </div>
                        <h4 class="fw-bold">Đăng Nhập</h4>
                        <p class="text-muted small">Truy cập để quản lý đơn hàng và ưu đãi của bạn</p>
                    </div>

                    <form action="{{ route('login') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="email" class="form-label fw-semibold">Địa chỉ Email</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-envelope text-muted"></i></span>
                                <input type="email" name="email" id="email" class="form-control border-start-0 @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="example@email.com" required autofocus>
                            </div>
                            @error('email')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label fw-semibold">Mật khẩu</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-key text-muted"></i></span>
                                <input type="password" name="password" id="password" class="form-control border-start-0 @error('password') is-invalid @enderror" placeholder="••••••••" required>
                            </div>
                            @error('password')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="form-check">
                                <input type="checkbox" name="remember" id="remember" class="form-check-input" {{ old('remember') ? 'checked' : '' }}>
                                <label for="remember" class="form-check-label small text-muted">Ghi nhớ đăng nhập</label>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-2 fs-6 fw-bold shadow-sm">
                            <i class="fa-solid fa-right-to-bracket me-2"></i> Đăng Nhập
                        </button>
                    </form>

                    <div class="text-center mt-4 pt-3 border-top">
                        <span class="text-muted small">Chưa có tài khoản? </span>
                        <a href="{{ route('register') }}" class="fw-bold text-decoration-none text-primary small">Đăng ký ngay</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
