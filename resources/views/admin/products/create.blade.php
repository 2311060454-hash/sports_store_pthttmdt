@extends('layouts.admin')

@section('title', 'Thêm Sản Phẩm Mới')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Thêm Sản Phẩm Mới</h4>
        <p class="text-muted small mb-0">Nhập đầy đủ thông số kỹ thuật, hình ảnh và thuộc tính sản phẩm</p>
    </div>
    <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary rounded-pill px-3">
        <i class="fa-solid fa-arrow-left me-1"></i> Quay lại
    </a>
</div>

<form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="row g-4">
        <!-- Main Info Left Column -->
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="fw-bold mb-0">Thông Tin Cơ Bản</h5>
                </div>
                <div class="card-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Tên sản phẩm <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Ví dụ: Giày Đá Bóng Nike Mercurial Vapor 15..." required>
                        @error('name')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Danh mục <span class="text-danger">*</span></label>
                            <select name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                                <option value="">-- Chọn danh mục --</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Thương hiệu <span class="text-danger">*</span></label>
                            <select name="brand_id" class="form-select @error('brand_id') is-invalid @enderror" required>
                                <option value="">-- Chọn thương hiệu --</option>
                                @foreach($brands as $b)
                                    <option value="{{ $b->id }}" {{ old('brand_id') == $b->id ? 'selected' : '' }}>{{ $b->name }}</option>
                                @endforeach
                            </select>
                            @error('brand_id')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Mã SKU <span class="text-danger">*</span></label>
                            <input type="text" name="sku" class="form-control font-monospace text-uppercase @error('sku') is-invalid @enderror" value="{{ old('sku') }}" placeholder="Vd: NIKE-MERC-01" required>
                            @error('sku')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-bold">Giá gốc (VNĐ) <span class="text-danger">*</span></label>
                            <input type="number" name="price" class="form-control @error('price') is-invalid @enderror" value="{{ old('price') }}" placeholder="1500000" min="0" required>
                            @error('price')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-bold">Giá khuyến mãi (VNĐ)</label>
                            <input type="number" name="sale_price" class="form-control @error('sale_price') is-invalid @enderror" value="{{ old('sale_price') }}" placeholder="1250000" min="0">
                            @error('sale_price')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Mô tả ngắn</label>
                        <textarea name="short_description" class="form-control" rows="2" placeholder="Tóm tắt đặc điểm nổi bật nhất của dụng cụ thể thao...">{{ old('short_description') }}</textarea>
                    </div>

                    <div class="mb-0">
                        <label class="form-label fw-bold">Mô tả chi tiết & Thông số kỹ thuật</label>
                        <textarea name="description" class="form-control" rows="6" placeholder="Chất liệu, trọng lượng, đối tượng sử dụng, hướng dẫn bảo quản...">{{ old('description') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Variants (Size / Color) -->
            <div class="card mb-4">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0">Phiên Bản / Biến Thể (Size & Màu Sắc)</h5>
                    <button type="button" class="btn btn-sm btn-outline-primary rounded-pill" onclick="addVariantRow()">
                        <i class="fa-solid fa-plus me-1"></i> Thêm biến thể
                    </button>
                </div>
                <div class="card-body p-4">
                    <div id="variantsContainer" class="d-flex flex-column gap-3">
                        <div class="row g-2 align-items-center variant-row">
                            <div class="col-md-3">
                                <input type="text" name="variants[0][size]" class="form-control form-control-sm" placeholder="Kích cỡ (Vd: 41, XL, 3U)">
                            </div>
                            <div class="col-md-3">
                                <input type="text" name="variants[0][color]" class="form-control form-control-sm" placeholder="Màu sắc (Vd: Đỏ, Trắng)">
                            </div>
                            <div class="col-md-2">
                                <input type="number" name="variants[0][quantity]" class="form-control form-control-sm" placeholder="Tồn kho" value="10" min="0">
                            </div>
                            <div class="col-md-3">
                                <input type="number" name="variants[0][additional_price]" class="form-control form-control-sm" placeholder="+ Phụ thu giá (đ)" value="0" min="0">
                            </div>
                            <div class="col-md-1 text-center">
                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeVariantRow(this)"><i class="fa-solid fa-xmark"></i></button>
                            </div>
                        </div>
                    </div>
                    <small class="text-muted mt-2 d-block">Lưu ý: Nếu không có biến thể size/màu, hệ thống sẽ sử dụng tổng số lượng tồn kho chung ở cột bên phải.</small>
                </div>
            </div>
        </div>

        <!-- Right Side: Images, Stock, Status -->
        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="fw-bold mb-0">Quản Lý Tồn & Trạng Thái</h5>
                </div>
                <div class="card-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Tổng số lượng tồn kho <span class="text-danger">*</span></label>
                        <input type="number" name="quantity" class="form-control @error('quantity') is-invalid @enderror" value="{{ old('quantity', 20) }}" min="0" required>
                        @error('quantity')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Trạng thái sản phẩm</label>
                        <select name="status" class="form-select">
                            <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>Đang kinh doanh (Active)</option>
                            <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Ngừng kinh doanh (Inactive)</option>
                        </select>
                    </div>

                    <div class="form-check form-switch mb-0">
                        <input class="form-check-input" type="checkbox" name="featured" value="1" id="featured" {{ old('featured') ? 'checked' : '' }}>
                        <label class="form-check-label fw-semibold" for="featured">Đánh dấu Sản phẩm Nổi bật</label>
                    </div>
                </div>
            </div>

            <!-- Images Upload Card -->
            <div class="card mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="fw-bold mb-0">Hình Ảnh Sản Phẩm</h5>
                </div>
                <div class="card-body p-4">
                    <div class="mb-4">
                        <label class="form-label fw-bold">Ảnh đại diện chính (Thumbnail) <span class="text-danger">*</span></label>
                        <input type="file" name="thumbnail" class="form-control @error('thumbnail') is-invalid @enderror" accept="image/*" required>
                        @error('thumbnail')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-0">
                        <label class="form-label fw-bold">Bộ sưu tập ảnh chi tiết (Gallery)</label>
                        <input type="file" name="images[]" class="form-control" accept="image/*" multiple>
                        <small class="text-muted">Có thể chọn nhiều hình ảnh cùng lúc (Góc chụp, đế giày, mặt vợt...)</small>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="card">
                <div class="card-body p-3">
                    <button type="submit" class="btn btn-primary w-100 py-2 rounded-pill fw-bold shadow">
                        <i class="fa-solid fa-floppy-disk me-1"></i> Lưu Sản Phẩm Mới
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
    let variantIndex = 1;
    function addVariantRow() {
        const container = document.getElementById('variantsContainer');
        const row = document.createElement('div');
        row.className = 'row g-2 align-items-center variant-row';
        row.innerHTML = `
            <div class="col-md-3">
                <input type="text" name="variants[${variantIndex}][size]" class="form-control form-control-sm" placeholder="Kích cỡ (Vd: 42, L, 4U)">
            </div>
            <div class="col-md-3">
                <input type="text" name="variants[${variantIndex}][color]" class="form-control form-control-sm" placeholder="Màu sắc (Vd: Xanh, Đen)">
            </div>
            <div class="col-md-2">
                <input type="number" name="variants[${variantIndex}][quantity]" class="form-control form-control-sm" placeholder="Tồn kho" value="10" min="0">
            </div>
            <div class="col-md-3">
                <input type="number" name="variants[${variantIndex}][additional_price]" class="form-control form-control-sm" placeholder="+ Phụ thu giá (đ)" value="0" min="0">
            </div>
            <div class="col-md-1 text-center">
                <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeVariantRow(this)"><i class="fa-solid fa-xmark"></i></button>
            </div>
        `;
        container.appendChild(row);
        variantIndex++;
    }

    function removeVariantRow(button) {
        button.closest('.variant-row').remove();
    }
</script>
@endpush
