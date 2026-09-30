@extends('layouts.admin')

@section('title', 'Thêm Danh Mục Mới')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Thêm Danh Mục Mới</h4>
        <p class="text-muted small mb-0">Tạo phân loại mới cho hệ thống sản phẩm thể thao</p>
    </div>
    <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary rounded-pill px-3">
        <i class="fa-solid fa-arrow-left me-1"></i> Quay lại
    </a>
</div>

<div class="card" style="max-width: 700px;">
    <div class="card-body p-4">
        <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label class="form-label fw-bold">Tên danh mục <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Ví dụ: Bóng đá, Cầu lông, Gym..." required>
                @error('name')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Ảnh đại diện danh mục</label>
                <input type="file" name="image" class="form-control @error('image') is-invalid @enderror" accept="image/*">
                @error('image')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Mô tả ngắn</label>
                <textarea name="description" class="form-control" rows="3" placeholder="Mô tả về môn thể thao hoặc danh mục này...">{{ old('description') }}</textarea>
            </div>

            <div class="mb-4">
                <label class="form-label fw-bold">Trạng thái hiển thị</label>
                <select name="status" class="form-select">
                    <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>Hiển thị (Active)</option>
                    <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Tạm ẩn (Inactive)</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold">
                <i class="fa-solid fa-floppy-disk me-1"></i> Lưu Danh Mục
            </button>
        </form>
    </div>
</div>
@endsection
