@extends('layouts.admin')

@section('title', 'Sửa Mã Giảm Giá - ' . $coupon->code)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Chỉnh Sửa Mã Giảm Giá</h4>
        <p class="text-muted small mb-0">{{ $coupon->code }} (ID: {{ $coupon->id }})</p>
    </div>
    <a href="{{ route('admin.coupons.index') }}" class="btn btn-outline-secondary rounded-pill px-3">
        <i class="fa-solid fa-arrow-left me-1"></i> Quay lại
    </a>
</div>

<div class="card" style="max-width: 750px;">
    <div class="card-body p-4">
        <form action="{{ route('admin.coupons.update', $coupon->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-bold">Mã Code <span class="text-danger">*</span></label>
                    <input type="text" name="code" class="form-control font-monospace text-uppercase @error('code') is-invalid @enderror" value="{{ old('code', $coupon->code) }}" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold">Loại chiết khấu <span class="text-danger">*</span></label>
                    <select name="type" class="form-select" required>
                        <option value="percent" {{ old('type', $coupon->type) === 'percent' ? 'selected' : '' }}>Giảm theo phần trăm (%)</option>
                        <option value="fixed" {{ old('type', $coupon->type) === 'fixed' ? 'selected' : '' }}>Giảm số tiền cố định (VNĐ)</option>
                    </select>
                </div>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-4">
                    <label class="form-label fw-bold">Giá trị giảm <span class="text-danger">*</span></label>
                    <input type="number" name="value" class="form-control" value="{{ old('value', $coupon->value) }}" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-bold">Đơn tối thiểu (VNĐ)</label>
                    <input type="number" name="min_order_amount" class="form-control" value="{{ old('min_order_amount', $coupon->min_order_amount) }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-bold">Giảm tối đa (nếu %)</label>
                    <input type="number" name="max_discount" class="form-control" value="{{ old('max_discount', $coupon->max_discount) }}">
                </div>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-bold">Ngày bắt đầu</label>
                    <input type="date" name="start_date" class="form-control" value="{{ old('start_date', $coupon->start_date ? $coupon->start_date->format('Y-m-d') : '') }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold">Ngày kết thúc</label>
                    <input type="date" name="end_date" class="form-control" value="{{ old('end_date', $coupon->end_date ? $coupon->end_date->format('Y-m-d') : '') }}">
                </div>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label fw-bold">Số lượng phát hành <span class="text-danger">*</span></label>
                    <input type="number" name="quantity" class="form-control" value="{{ old('quantity', $coupon->quantity) }}" min="1" required>
                    <small class="text-muted">Đã sử dụng: <strong>{{ $coupon->used }}</strong> lượt</small>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold">Trạng thái</label>
                    <select name="status" class="form-select">
                        <option value="active" {{ old('status', $coupon->status) === 'active' ? 'selected' : '' }}>Kích hoạt (Active)</option>
                        <option value="inactive" {{ old('status', $coupon->status) === 'inactive' ? 'selected' : '' }}>Tắt (Inactive)</option>
                    </select>
                </div>
            </div>

            <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold">
                <i class="fa-solid fa-floppy-disk me-1"></i> Cập Nhật Mã Giảm Giá
            </button>
        </form>
    </div>
</div>
@endsection
