@extends('layouts.admin')

@section('title', 'Thêm Thương Hiệu Mới')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Thêm Thương Hiệu Mới</h4>
        <p class="text-muted small mb-0">Tạo nhãn hiệu thể thao mới trong hệ thống</p>
    </div>
    <a href="{{ route('admin.brands.index') }}" class="btn btn-outline-secondary rounded-pill px-3">
        <i class="fa-solid fa-arrow-left me-1"></i> Quay lại
    </a>
</div>

<div class="card" style="max-width: 700px;">
    <div class="card-body p-4">
        <form action="{{ route('admin.brands.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label class="form-label fw-bold">Tên thương hiệu <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Ví dụ: Nike, Adidas, Yonex..." required>
                @error('name')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Logo thương hiệu</label>
                <input type="file" name="logo" class="form-control @error('logo') is-invalid @enderror" accept="image/*">
                @error('logo')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Mô tả thương hiệu</label>
                <textarea name="description" class="form-control" rows="3" placeholder="Giới thiệu xuất xứ, thế mạnh thương hiệu...">{{ old('description') }}</textarea>
            </div>

            <div class="mb-4">
                <label class="form-label fw-bold">Trạng thái</label>
                <select name="status" class="form-select">
                    <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>Hoạt động (Active)</option>
                    <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Tạm ẩn (Inactive)</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold">
                <i class="fa-solid fa-floppy-disk me-1"></i> Lưu Thương Hiệu
            </button>
        </form>
    </div>
</div>
@endsection
