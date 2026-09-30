@extends('layouts.admin')

@section('title', 'Sửa Sản Phẩm - ' . $product->name)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Chỉnh Sửa Sản Phẩm</h4>
        <p class="text-muted small mb-0">{{ $product->name }} (ID: {{ $product->id }})</p>
    </div>
    <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary rounded-pill px-3">
        <i class="fa-solid fa-arrow-left me-1"></i> Quay lại
    </a>
</div>

<form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

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
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $product->name) }}" required>
                        @error('name')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Danh mục <span class="text-danger">*</span></label>
                            <select name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Thương hiệu <span class="text-danger">*</span></label>
                            <select name="brand_id" class="form-select @error('brand_id') is-invalid @enderror" required>
                                @foreach($brands as $b)
                                    <option value="{{ $b->id }}" {{ old('brand_id', $product->brand_id) == $b->id ? 'selected' : '' }}>{{ $b->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Mã SKU <span class="text-danger">*</span></label>
                            <input type="text" name="sku" class="form-control font-monospace text-uppercase @error('sku') is-invalid @enderror" value="{{ old('sku', $product->sku) }}" required>
                            @error('sku')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-bold">Giá gốc (VNĐ) <span class="text-danger">*</span></label>
                            <input type="number" name="price" class="form-control @error('price') is-invalid @enderror" value="{{ old('price', $product->price) }}" min="0" required>
                            @error('price')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-bold">Giá khuyến mãi (VNĐ)</label>
                            <input type="number" name="sale_price" class="form-control @error('sale_price') is-invalid @enderror" value="{{ old('sale_price', $product->sale_price) }}" min="0">
                            @error('sale_price')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Mô tả ngắn</label>
                        <textarea name="short_description" class="form-control" rows="2">{{ old('short_description', $product->short_description) }}</textarea>
                    </div>

                    <div class="mb-0">
                        <label class="form-label fw-bold">Mô tả chi tiết & Thông số</label>
                        <textarea name="description" class="form-control" rows="6">{{ old('description', $product->description) }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Existing & New Variants -->
            <div class="card mb-4">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0">Biến Thể Sản Phẩm (Size & Màu Sắc)</h5>
                    <button type="button" class="btn btn-sm btn-outline-primary rounded-pill" onclick="addVariantRow()">
                        <i class="fa-solid fa-plus me-1"></i> Thêm biến thể
                    </button>
                </div>
                <div class="card-body p-4">
                    <div id="variantsContainer" class="d-flex flex-column gap-3">
                        @forelse($product->variants as $index => $var)
                            <div class="row g-2 align-items-center variant-row">
                                <input type="hidden" name="variants[{{ $index }}][id]" value="{{ $var->id }}">
                                <div class="col-md-3">
                                    <input type="text" name="variants[{{ $index }}][size]" class="form-control form-control-sm" placeholder="Kích cỡ" value="{{ $var->size }}">
                                </div>
                                <div class="col-md-3">
                                    <input type="text" name="variants[{{ $index }}][color]" class="form-control form-control-sm" placeholder="Màu sắc" value="{{ $var->color }}">
                                </div>
                                <div class="col-md-2">
                                    <input type="number" name="variants[{{ $index }}][quantity]" class="form-control form-control-sm" placeholder="Tồn" value="{{ $var->quantity }}" min="0">
                                </div>
                                <div class="col-md-3">
                                    <input type="number" name="variants[{{ $index }}][additional_price]" class="form-control form-control-sm" placeholder="+ Giá (đ)" value="{{ $var->additional_price }}" min="0">
                                </div>
                                <div class="col-md-1 text-center">
                                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeVariantRow(this)"><i class="fa-solid fa-xmark"></i></button>
                                </div>
                            </div>
                        @empty
                            <div class="row g-2 align-items-center variant-row">
                                <div class="col-md-3">
                                    <input type="text" name="variants[0][size]" class="form-control form-control-sm" placeholder="Kích cỡ">
                                </div>
                                <div class="col-md-3">
                                    <input type="text" name="variants[0][color]" class="form-control form-control-sm" placeholder="Màu sắc">
                                </div>
                                <div class="col-md-2">
                                    <input type="number" name="variants[0][quantity]" class="form-control form-control-sm" placeholder="Tồn" value="10" min="0">
                                </div>
                                <div class="col-md-3">
                                    <input type="number" name="variants[0][additional_price]" class="form-control form-control-sm" placeholder="+ Giá (đ)" value="0" min="0">
                                </div>
                                <div class="col-md-1 text-center">
                                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeVariantRow(this)"><i class="fa-solid fa-xmark"></i></button>
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side: Images, Stock, Status -->
        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="fw-bold mb-0">Tồn Kho & Trạng Thái</h5>
                </div>
                <div class="card-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Tổng số lượng tồn <span class="text-danger">*</span></label>
                        <input type="number" name="quantity" class="form-control @error('quantity') is-invalid @enderror" value="{{ old('quantity', $product->quantity) }}" min="0" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Trạng thái kinh doanh</label>
                        <select name="status" class="form-select">
                            <option value="active" {{ old('status', $product->status) === 'active' ? 'selected' : '' }}>Đang kinh doanh (Active)</option>
                            <option value="inactive" {{ old('status', $product->status) === 'inactive' ? 'selected' : '' }}>Ngừng kinh doanh (Inactive)</option>
                        </select>
                    </div>

                    <div class="form-check form-switch mb-0">
                        <input class="form-check-input" type="checkbox" name="featured" value="1" id="featured" {{ old('featured', $product->featured) ? 'checked' : '' }}>
                        <label class="form-check-label fw-semibold" for="featured">Sản phẩm Nổi bật</label>
                    </div>
                </div>
            </div>

            <!-- Thumbnail Card -->
            <div class="card mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="fw-bold mb-0">Ảnh Đại Diện (Thumbnail)</h5>
                </div>
                <div class="card-body p-4">
                    @if($product->thumbnail)
                        <div class="mb-3">
                            <img src="{{ asset($product->thumbnail) }}" alt="{{ $product->name }}" class="rounded-3 border w-100" style="max-height: 200px; object-fit: contain;">
                        </div>
                    @endif
                    <input type="file" name="thumbnail" class="form-control" accept="image/*">
                    <small class="text-muted">Chọn ảnh mới nếu muốn thay đổi.</small>
                </div>
            </div>

            <!-- Gallery Images Card -->
            <div class="card mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="fw-bold mb-0">Ảnh Chi Tiết (Gallery)</h5>
                </div>
                <div class="card-body p-4">
                    @if($product->images->count() > 0)
                        <div class="d-flex flex-wrap gap-2 mb-3">
                            @foreach($product->images as $img)
                                <div class="position-relative border rounded-3 p-1" style="width: 70px; height: 70px;">
                                    <img src="{{ asset($img->image) }}" class="w-100 h-100 rounded-2" style="object-fit: cover;">
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <label class="form-label fw-semibold small">Tải thêm ảnh vào Gallery:</label>
                    <input type="file" name="images[]" class="form-control" accept="image/*" multiple>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="card">
                <div class="card-body p-3">
                    <button type="submit" class="btn btn-primary w-100 py-2 rounded-pill fw-bold shadow">
                        <i class="fa-solid fa-floppy-disk me-1"></i> Cập Nhật Sản Phẩm
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
    let variantIndex = {{ $product->variants->count() + 10 }};
    function addVariantRow() {
        const container = document.getElementById('variantsContainer');
        const row = document.createElement('div');
        row.className = 'row g-2 align-items-center variant-row';
        row.innerHTML = `
            <div class="col-md-3">
                <input type="text" name="variants[${variantIndex}][size]" class="form-control form-control-sm" placeholder="Kích cỡ">
            </div>
            <div class="col-md-3">
                <input type="text" name="variants[${variantIndex}][color]" class="form-control form-control-sm" placeholder="Màu sắc">
            </div>
            <div class="col-md-2">
                <input type="number" name="variants[${variantIndex}][quantity]" class="form-control form-control-sm" placeholder="Tồn" value="10" min="0">
            </div>
            <div class="col-md-3">
                <input type="number" name="variants[${variantIndex}][additional_price]" class="form-control form-control-sm" placeholder="+ Giá (đ)" value="0" min="0">
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
