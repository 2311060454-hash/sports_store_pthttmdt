@extends('layouts.admin')

@section('title', 'Thêm Banner Mới')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Thêm Banner Mới</h4>
        <p class="text-muted small mb-0">Tải lên hình ảnh banner và cấu hình liên kết</p>
    </div>
    <a href="{{ route('admin.banners.index') }}" class="btn btn-outline-secondary rounded-pill px-3">
        <i class="fa-solid fa-arrow-left me-1"></i> Quay lại
    </a>
</div>

<div class="card" style="max-width: 700px;">
    <div class="card-body p-4">
        <form action="{{ route('admin.banners.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label class="form-label fw-bold">Tiêu đề banner <span class="text-danger">*</span></label>
                <input type="text" name="title" class="form-control" placeholder="Ví dụ: Giày Đá Bóng Chính Hãng 2026..." required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Hình ảnh banner <span class="text-danger">*</span></label>
                <input type="file" name="image" class="form-control" accept="image/*" required>
                <small class="text-muted">Kích thước khuyến nghị: 1600x500px để hiển thị đẹp nhất.</small>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Liên kết khi click (URL)</label>
                <input type="text" name="link" class="form-control" placeholder="Ví dụ: /products hoặc https://...">
            </div>

            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label fw-bold">Vị trí</label>
                    <select name="position" class="form-select">
                        <option value="home_top">Đầu trang chủ (Slider)</option>
                        <option value="home_middle">Giữa trang chủ</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold">Trạng thái</label>
                    <select name="status" class="form-select">
                        <option value="active">Hiển thị (Active)</option>
                        <option value="inactive">Tạm ẩn (Inactive)</option>
                    </select>
                </div>
            </div>

            <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold">
                <i class="fa-solid fa-floppy-disk me-1"></i> Lưu Banner
            </button>
        </form>
    </div>
</div>
@endsection
