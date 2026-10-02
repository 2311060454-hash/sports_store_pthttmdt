@extends('layouts.admin')

@section('title', 'Chỉnh Sửa Người Dùng #' . $user->id)

@section('content')
<div class="mb-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-1 small">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none text-muted">Quản trị</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}" class="text-decoration-none text-muted">Người dùng</a></li>
            <li class="breadcrumb-item active text-primary fw-semibold" aria-current="page">Chỉnh sửa</li>
        </ol>
    </nav>
    <h4 class="fw-bold mb-0">Chỉnh Sửa Người Dùng: {{ $user->name }}</h4>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4 p-4">
            <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label fw-semibold">Họ và tên <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Địa chỉ Email <span class="text-danger">*</span></label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Mật khẩu mới (Bỏ trống nếu không muốn đổi)</label>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Nhập mật khẩu mới nếu cần đổi">
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold">Vai trò <span class="text-danger">*</span></label>
                    <select name="role" class="form-select @error('role') is-invalid @enderror" required>
                        <option value="user" {{ old('role', $user->role) === 'user' || old('role', $user->role) === 'customer' ? 'selected' : '' }}>Người dùng / Khách hàng (User)</option>
                        <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Quản trị viên (Admin)</option>
                    </select>
                    @error('role')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold">
                        <i class="fa-solid fa-check me-1"></i> Cập nhật
                    </button>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-light rounded-pill px-4">
                        ← Quay lại
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
