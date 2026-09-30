@extends('frontend.account.layout')

@section('title', 'Thông Tin Cá Nhân - Sport Store')

@section('account_breadcrumb')
    <li class="breadcrumb-item active text-primary fw-semibold" aria-current="page">Thông tin cá nhân</li>
@endsection

@section('account_content')
<div class="card border-0 shadow-sm rounded-4 p-4 p-md-5">
    <h4 class="fw-bold mb-4">Cập Nhật Thông Tin Cá Nhân</h4>

    <form action="{{ route('account.profile.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row g-4 mb-4">
            <div class="col-md-4 text-center">
                <div class="mb-3">
                    @if($user->avatar)
                        <img src="{{ asset($user->avatar) }}" alt="{{ $user->name }}" class="rounded-circle border" style="width: 120px; height: 120px; object-fit: cover;">
                    @else
                        <div class="rounded-circle bg-warning-subtle text-warning fw-bold d-flex align-items-center justify-content-center mx-auto" style="width: 120px; height: 120px; font-size: 3rem;">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                    @endif
                </div>
                <label for="avatar" class="btn btn-sm btn-outline-secondary rounded-pill">
                    <i class="fa-solid fa-camera me-1"></i> Chọn ảnh mới
                </label>
                <input type="file" name="avatar" id="avatar" class="d-none" accept="image/*">
                @error('avatar')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-8">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Họ và tên <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required>
                    @error('name')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Địa chỉ Email</label>
                    <input type="email" class="form-control bg-light" value="{{ $user->email }}" readonly disabled>
                    <small class="text-muted">Email đăng nhập không thể thay đổi.</small>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Số điện thoại <span class="text-danger">*</span></label>
                    <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', $user->phone) }}" required>
                    @error('phone')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <div class="text-end">
            <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold">
                <i class="fa-solid fa-floppy-disk me-2"></i> Lưu Thay Đổi
            </button>
        </div>
    </form>
</div>
@endsection
