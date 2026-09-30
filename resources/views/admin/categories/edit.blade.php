@extends('layouts.admin')

@section('title', 'Sửa Danh Mục - ' . $category->name)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Chỉnh Sửa Danh Mục</h4>
        <p class="text-muted small mb-0">{{ $category->name }} (ID: {{ $category->id }})</p>
    </div>
    <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary rounded-pill px-3">
        <i class="fa-solid fa-arrow-left me-1"></i> Quay lại
    </a>
</div>

<div class="card" style="max-width: 700px;">
    <div class="card-body p-4">
        <form action="{{ route('admin.categories.update', $category->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label fw-bold">Tên danh mục <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $category->name) }}" required>
                @error('name')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Ảnh đại diện danh mục</label>
                @if($category->image)
                    <div class="mb-2">
                        <img src="{{ asset($category->image) }}" alt="{{ $category->name }}" class="rounded-3 border" style="width: 80px; height: 80px; object-fit: cover;">
                    </div>
                @endif
                <input type="file" name="image" class="form-control @error('image') is-invalid @enderror" accept="image/*">
                <small class="text-muted">Chọn tệp mới nếu muốn thay đổi ảnh.</small>
                @error('image')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Mô tả ngắn</label>
                <textarea name="description" class="form-control" rows="3">{{ old('description', $category->description) }}</textarea>
            </div>

            <div class="mb-4">
                <label class="form-label fw-bold">Trạng thái hiển thị</label>
                <select name="status" class="form-select">
                    <option value="active" {{ old('status', $category->status) === 'active' ? 'selected' : '' }}>Hiển thị (Active)</option>
                    <option value="inactive" {{ old('status', $category->status) === 'inactive' ? 'selected' : '' }}>Tạm ẩn (Inactive)</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold">
                <i class="fa-solid fa-floppy-disk me-1"></i> Cập Nhật Danh Mục
            </button>
        </form>
    </div>
</div>
@endsection
