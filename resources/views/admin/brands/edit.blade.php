@extends('layouts.admin')

@section('title', 'Sửa Thương Hiệu - ' . $brand->name)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Chỉnh Sửa Thương Hiệu</h4>
        <p class="text-muted small mb-0">{{ $brand->name }} (ID: {{ $brand->id }})</p>
    </div>
    <a href="{{ route('admin.brands.index') }}" class="btn btn-outline-secondary rounded-pill px-3">
        <i class="fa-solid fa-arrow-left me-1"></i> Quay lại
    </a>
</div>

<div class="card" style="max-width: 700px;">
    <div class="card-body p-4">
        <form action="{{ route('admin.brands.update', $brand->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label fw-bold">Tên thương hiệu <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $brand->name) }}" required>
                @error('name')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Logo thương hiệu</label>
                @if($brand->logo)
                    <div class="mb-2">
                        <img src="{{ asset($brand->logo) }}" alt="{{ $brand->name }}" class="rounded-3 border" style="width: 70px; height: 70px; object-fit: contain; background: #fff;">
                    </div>
                @endif
                <input type="file" name="logo" class="form-control @error('logo') is-invalid @enderror" accept="image/*">
                <small class="text-muted">Chọn tệp mới nếu muốn thay đổi logo.</small>
                @error('logo')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Mô tả thương hiệu</label>
                <textarea name="description" class="form-control" rows="3">{{ old('description', $brand->description) }}</textarea>
            </div>

            <div class="mb-4">
                <label class="form-label fw-bold">Trạng thái</label>
                <select name="status" class="form-select">
                    <option value="active" {{ old('status', $brand->status) === 'active' ? 'selected' : '' }}>Hoạt động (Active)</option>
                    <option value="inactive" {{ old('status', $brand->status) === 'inactive' ? 'selected' : '' }}>Tạm ẩn (Inactive)</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold">
                <i class="fa-solid fa-floppy-disk me-1"></i> Cập Nhật Thương Hiệu
            </button>
        </form>
    </div>
</div>
@endsection
