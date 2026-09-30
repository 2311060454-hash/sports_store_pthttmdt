@extends('layouts.admin')

@section('title', 'Quản Lý Sản Phẩm')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Kho Dụng Cụ Thể Thao</h4>
        <p class="text-muted small mb-0">Quản lý toàn bộ danh sách sản phẩm, giá bán, biến thể và số lượng tồn</p>
    </div>
    <a href="{{ route('admin.products.create') }}" class="btn btn-primary rounded-pill px-4 fw-bold">
        <i class="fa-solid fa-plus me-1"></i> Thêm Sản Phẩm Mới
    </a>
</div>

<!-- Search & Filter Card -->
<div class="card mb-4">
    <div class="card-body p-3">
        <form action="{{ route('admin.products.index') }}" method="GET" class="row g-2 align-items-center">
            <div class="col-md-3">
                <input type="text" name="q" class="form-control form-control-sm" placeholder="Tìm tên hoặc SKU..." value="{{ request('q') }}">
            </div>
            <div class="col-md-3">
                <select name="category_id" class="form-select form-select-sm">
                    <option value="">Tất cả danh mục</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="brand_id" class="form-select form-select-sm">
                    <option value="">Tất cả thương hiệu</option>
                    @foreach($brands as $b)
                        <option value="{{ $b->id }}" {{ request('brand_id') == $b->id ? 'selected' : '' }}>{{ $b->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="stock_status" class="form-select form-select-sm">
                    <option value="">Tất cả tồn kho</option>
                    <option value="in_stock" {{ request('stock_status') === 'in_stock' ? 'selected' : '' }}>Còn hàng (> 5)</option>
                    <option value="low_stock" {{ request('stock_status') === 'low_stock' ? 'selected' : '' }}>Sắp hết hàng (≤ 5)</option>
                    <option value="out_of_stock" {{ request('stock_status') === 'out_of_stock' ? 'selected' : '' }}>Hết hàng (0)</option>
                </select>
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-sm btn-primary w-100 rounded-pill">Lọc</button>
                <a href="{{ route('admin.products.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill">Reset</a>
            </div>
        </form>
    </div>
</div>

<!-- Products Table Card -->
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="px-3" style="width: 60px;">ID</th>
                        <th>Hình ảnh</th>
                        <th>Tên sản phẩm / SKU</th>
                        <th>Danh mục & Hãng</th>
                        <th>Giá bán / Sale</th>
                        <th class="text-center">Tồn kho</th>
                        <th class="text-center">Đã bán</th>
                        <th>Trạng thái</th>
                        <th class="text-end px-3">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                        <tr>
                            <td class="px-3 font-monospace text-muted">{{ $product->id }}</td>
                            <td>
                                <img src="{{ asset($product->thumbnail ?? 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=100') }}" 
                                     alt="{{ $product->name }}" 
                                     class="rounded-3 border" 
                                     style="width: 50px; height: 50px; object-fit: cover;">
                            </td>
                            <td>
                                <div class="fw-bold text-dark text-truncate" style="max-width: 220px;" title="{{ $product->name }}">
                                    {{ $product->name }}
                                </div>
                                <span class="badge bg-light text-secondary font-monospace border small">SKU: {{ $product->sku }}</span>
                                @if($product->featured)
                                    <span class="badge bg-warning text-dark small ms-1">Nổi bật</span>
                                @endif
                            </td>
                            <td class="small">
                                <div><i class="fa-solid fa-layer-group text-muted me-1"></i> {{ $product->category->name ?? '-' }}</div>
                                <div class="text-muted"><i class="fa-solid fa-tag text-muted me-1"></i> {{ $product->brand->name ?? '-' }}</div>
                            </td>
                            <td>
                                <div class="fw-bold text-primary">{{ number_format($product->final_price, 0, ',', '.') }} ₫</div>
                                @if($product->sale_price && $product->sale_price < $product->price)
                                    <small class="text-muted text-decoration-line-through">{{ number_format($product->price, 0, ',', '.') }} ₫</small>
                                @endif
                            </td>
                            <td class="text-center font-monospace">
                                @if($product->quantity == 0)
                                    <span class="badge bg-danger">Hết hàng</span>
                                @elseif($product->quantity <= 5)
                                    <span class="badge bg-warning text-dark">{{ $product->quantity }} (Sắp hết)</span>
                                @else
                                    <span class="badge bg-success-subtle text-success">{{ $product->quantity }}</span>
                                @endif
                            </td>
                            <td class="text-center font-monospace fw-bold text-secondary">{{ $product->sold }}</td>
                            <td>
                                <span class="badge {{ $product->status === 'active' ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $product->status === 'active' ? 'Hoạt động' : 'Tạm ẩn' }}
                                </span>
                            </td>
                            <td class="text-end px-3">
                                <a href="{{ route('products.show', $product->slug) }}" target="_blank" class="btn btn-sm btn-outline-info btn-action me-1" title="Xem trên web">
                                    <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                </a>

                                <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-sm btn-outline-secondary btn-action me-1" title="Sửa">
                                    <i class="fa-solid fa-pen"></i>
                                </a>

                                <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" class="d-inline form-delete-confirm">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger btn-action" title="Xóa">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-4 text-muted">Không tìm thấy sản phẩm nào.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($products->hasPages())
        <div class="card-footer bg-white py-3 d-flex justify-content-center">
            {{ $products->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>
@endsection
