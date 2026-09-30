@extends('layouts.app')

@section('title', 'Đăng Ký Tài Khoản - Sport Store')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-6">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body p-4 p-md-5">
                    <div class="text-center mb-4">
                        <div class="bg-primary-subtle text-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width: 60px; height: 60px;">
                            <i class="fa-solid fa-user-plus fs-3 text-warning"></i>
                        </div>
                        <h4 class="fw-bold">Tạo Tài Khoản</h4>
                        <p class="text-muted small">Đăng ký để nhận hàng loạt ưu đãi và theo dõi đơn hàng tiện lợi</p>
                    </div>

                    <form action="{{ route('register') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="name" class="form-label fw-semibold">Họ và tên <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-user text-muted"></i></span>
                                <input type="text" name="name" id="name" class="form-control border-start-0 @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Nguyễn Văn A" required>
                            </div>
                            @error('name')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label for="email" class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-envelope text-muted"></i></span>
                                    <input type="email" name="email" id="email" class="form-control border-start-0 @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="example@email.com" required>
                                </div>
                                @error('email')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="phone" class="form-label fw-semibold">Số điện thoại <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-phone text-muted"></i></span>
                                    <input type="text" name="phone" id="phone" class="form-control border-start-0 @error('phone') is-invalid @enderror" value="{{ old('phone') }}" placeholder="0987654321" required>
                                </div>
                                @error('phone')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label for="password" class="form-label fw-semibold">Mật khẩu <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-lock text-muted"></i></span>
                                    <input type="password" name="password" id="password" class="form-control border-start-0 @error('password') is-invalid @enderror" placeholder="Tối thiểu 8 ký tự" required>
                                </div>
                                @error('password')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="password_confirmation" class="form-label fw-semibold">Nhập lại mật khẩu <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-check text-muted"></i></span>
                                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control border-start-0" placeholder="••••••••" required>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-2 fs-6 fw-bold shadow-sm">
                            <i class="fa-solid fa-user-check me-2"></i> Hoàn Tất Đăng Ký
                        </button>
                    </form>

                    <div class="text-center mt-4 pt-3 border-top">
                        <span class="text-muted small">Đã có tài khoản? </span>
                        <a href="{{ route('login') }}" class="fw-bold text-decoration-none text-primary small">Đăng nhập</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
