@extends('frontend.account.layout')

@section('title', 'Đổi Mật Khẩu - Sport Store')

@section('account_breadcrumb')
    <li class="breadcrumb-item active text-primary fw-semibold" aria-current="page">Đổi mật khẩu</li>
@endsection

@section('account_content')
<div class="card border-0 shadow-sm rounded-4 p-4 p-md-5">
    <h4 class="fw-bold mb-4">Đổi Mật Khẩu Tài Khoản</h4>

    <form action="{{ route('account.password.update') }}" method="POST" style="max-width: 500px;">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label fw-semibold">Mật khẩu hiện tại <span class="text-danger">*</span></label>
            <input type="password" name="current_password" class="form-control @error('current_password') is-invalid @enderror" placeholder="••••••••" required>
            @error('current_password')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label fw-semibold">Mật khẩu mới <span class="text-danger">*</span></label>
            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Tối thiểu 8 ký tự" required>
            @error('password')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-4">
            <label class="form-label fw-semibold">Xác nhận mật khẩu mới <span class="text-danger">*</span></label>
            <input type="password" name="password_confirmation" class="form-control" placeholder="••••••••" required>
        </div>

        <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold">
            <i class="fa-solid fa-key me-2"></i> Cập Nhật Mật Khẩu
        </button>
    </form>
</div>
@endsection
