@extends('layouts.admin')

@section('title', 'Sửa Banner - ' . $banner->title)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Chỉnh Sửa Banner</h4>
        <p class="text-muted small mb-0">{{ $banner->title }} (ID: {{ $banner->id }})</p>
    </div>
    <a href="{{ route('admin.banners.index') }}" class="btn btn-outline-secondary rounded-pill px-3">
        <i class="fa-solid fa-arrow-left me-1"></i> Quay lại
    </a>
</div>

<div class="card" style="max-width: 700px;">
    <div class="card-body p-4">
        <form action="{{ route('admin.banners.update', $banner->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label fw-bold">Tiêu đề banner <span class="text-danger">*</span></label>
                <input type="text" name="title" class="form-control" value="{{ old('title', $banner->title) }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Hình ảnh banner</label>
                @if($banner->image)
                    <div class="mb-2">
                        <img src="{{ asset($banner->image) }}" class="rounded-3 border w-100" style="max-height: 160px; object-fit: cover;">
                    </div>
                @endif
                <input type="file" name="image" class="form-control" accept="image/*">
                <small class="text-muted">Chọn ảnh mới nếu muốn thay thế ảnh hiện tại.</small>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Liên kết khi click (URL)</label>
                <input type="text" name="link" class="form-control" value="{{ old('link', $banner->link) }}">
            </div>

            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label fw-bold">Vị trí</label>
                    <select name="position" class="form-select">
                        <option value="home_top" {{ old('position', $banner->position) === 'home_top' ? 'selected' : '' }}>Đầu trang chủ (Slider)</option>
                        <option value="home_middle" {{ old('position', $banner->position) === 'home_middle' ? 'selected' : '' }}>Giữa trang chủ</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold">Trạng thái</label>
                    <select name="status" class="form-select">
                        <option value="active" {{ old('status', $banner->status) === 'active' ? 'selected' : '' }}>Hiển thị (Active)</option>
                        <option value="inactive" {{ old('status', $banner->status) === 'inactive' ? 'selected' : '' }}>Tạm ẩn (Inactive)</option>
                    </select>
                </div>
            </div>

            <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold">
                <i class="fa-solid fa-floppy-disk me-1"></i> Cập Nhật Banner
            </button>
        </form>
    </div>
</div>
@endsection
