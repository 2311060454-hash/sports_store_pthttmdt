@extends('layouts.admin')

@section('title', 'Tạo Mã Giảm Giá Mới')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Tạo Mã Giảm Giá Mới</h4>
        <p class="text-muted small mb-0">Thiết lập chính sách khuyến mại và giới hạn lượt sử dụng</p>
    </div>
    <a href="{{ route('admin.coupons.index') }}" class="btn btn-outline-secondary rounded-pill px-3">
        <i class="fa-solid fa-arrow-left me-1"></i> Quay lại
    </a>
</div>

<div class="card" style="max-width: 750px;">
    <div class="card-body p-4">
        <form action="{{ route('admin.coupons.store') }}" method="POST">
            @csrf

            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-bold">Mã Code <span class="text-danger">*</span></label>
                    <input type="text" name="code" class="form-control font-monospace text-uppercase @error('code') is-invalid @enderror" value="{{ old('code') }}" placeholder="Ví dụ: SPORT10" required>
                    @error('code')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold">Loại chiết khấu <span class="text-danger">*</span></label>
                    <select name="type" class="form-select" required>
                        <option value="percent" {{ old('type') === 'percent' ? 'selected' : '' }}>Giảm theo phần trăm (%)</option>
                        <option value="fixed" {{ old('type') === 'fixed' ? 'selected' : '' }}>Giảm số tiền cố định (VNĐ)</option>
                    </select>
                </div>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-4">
                    <label class="form-label fw-bold">Giá trị giảm <span class="text-danger">*</span></label>
                    <input type="number" name="value" class="form-control @error('value') is-invalid @enderror" value="{{ old('value') }}" placeholder="Vd: 10 (%) hoặc 50000 (đ)" min="0" required>
                    @error('value')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-bold">Đơn tối thiểu (VNĐ)</label>
                    <input type="number" name="min_order_amount" class="form-control" value="{{ old('min_order_amount', 0) }}" min="0">
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-bold">Giảm tối đa (nếu %)</label>
                    <input type="number" name="max_discount" class="form-control" value="{{ old('max_discount') }}" placeholder="Vd: 100000" min="0">
                </div>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-bold">Ngày bắt đầu</label>
                    <input type="date" name="start_date" class="form-control" value="{{ old('start_date') }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold">Ngày kết thúc</label>
                    <input type="date" name="end_date" class="form-control @error('end_date') is-invalid @enderror" value="{{ old('end_date') }}">
                    @error('end_date')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label fw-bold">Số lượng phát hành <span class="text-danger">*</span></label>
                    <input type="number" name="quantity" class="form-control @error('quantity') is-invalid @enderror" value="{{ old('quantity', 100) }}" min="1" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold">Trạng thái</label>
                    <select name="status" class="form-select">
                        <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>Kích hoạt (Active)</option>
                        <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Tắt (Inactive)</option>
                    </select>
                </div>
            </div>

            <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold">
                <i class="fa-solid fa-floppy-disk me-1"></i> Lưu Mã Giảm Giá
            </button>
        </form>
    </div>
</div>
@endsection
