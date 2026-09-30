@extends('layouts.admin')

@section('title', 'Quản Lý Tồn Kho')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Kiểm Soát Tồn Kho & Hàng Hóa</h4>
        <p class="text-muted small mb-0">Theo dõi số lượng hàng tồn, cảnh báo hết hàng và cập nhật tồn nhanh</p>
    </div>
</div>

<!-- Stock summary cards -->
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <a href="{{ route('admin.inventory.index', ['filter' => 'all']) }}" class="text-decoration-none">
            <div class="card p-3 border-start border-4 border-primary {{ $filter === 'all' ? 'shadow-sm bg-primary-subtle' : '' }}">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-muted small fw-bold">Còn Hàng Bình Thường</span>
                        <h4 class="fw-bold text-primary mb-0 mt-1">{{ $inStockCount }} sản phẩm</h4>
                    </div>
                    <i class="fa-solid fa-boxes-stacked fs-3 text-primary"></i>
                </div>
            </div>
        </a>
    </div>

    <div class="col-md-4">
        <a href="{{ route('admin.inventory.index', ['filter' => 'low_stock']) }}" class="text-decoration-none">
            <div class="card p-3 border-start border-4 border-warning {{ $filter === 'low_stock' ? 'shadow-sm bg-warning-subtle' : '' }}">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-warning small fw-bold">Cảnh Báo Sắp Hết (≤ 5)</span>
                        <h4 class="fw-bold text-warning mb-0 mt-1">{{ $lowStockCount }} sản phẩm</h4>
                    </div>
                    <i class="fa-solid fa-triangle-exclamation fs-3 text-warning"></i>
                </div>
            </div>
        </a>
    </div>

    <div class="col-md-4">
        <a href="{{ route('admin.inventory.index', ['filter' => 'out_of_stock']) }}" class="text-decoration-none">
            <div class="card p-3 border-start border-4 border-danger {{ $filter === 'out_of_stock' ? 'shadow-sm bg-danger-subtle' : '' }}">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-danger small fw-bold">Đã Hết Hàng (0)</span>
                        <h4 class="fw-bold text-danger mb-0 mt-1">{{ $outOfStockCount }} sản phẩm</h4>
                    </div>
                    <i class="fa-solid fa-circle-xmark fs-3 text-danger"></i>
                </div>
            </div>
        </a>
    </div>
</div>

<!-- Inventory List Table -->
<div class="card">
    <div class="card-header bg-white py-3">
        <form action="{{ route('admin.inventory.index') }}" method="GET" class="row g-2 align-items-center">
            <input type="hidden" name="filter" value="{{ $filter }}">
            <div class="col-md-4">
                <input type="text" name="q" class="form-control form-control-sm" placeholder="Tìm kiếm theo mã SKU hoặc tên sản phẩm..." value="{{ request('q') }}">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-sm btn-primary w-100 rounded-pill">Tìm kiếm</button>
            </div>
        </form>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="px-3">Mã SKU</th>
                        <th>Sản phẩm</th>
                        <th>Danh mục</th>
                        <th class="text-center">Số lượng tồn</th>
                        <th class="text-center">Cảnh báo kho</th>
                        <th class="text-center">Đã bán</th>
                        <th class="text-end px-3">Cập nhật nhanh số lượng</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                        <tr class="{{ $product->quantity == 0 ? 'table-danger-subtle' : ($product->quantity <= 5 ? 'table-warning-subtle' : '') }}">
                            <td class="px-3 font-monospace fw-bold text-secondary">{{ $product->sku }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="{{ asset($product->thumbnail ?? 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=100') }}" 
                                         class="rounded-3 border me-2" 
                                         style="width: 44px; height: 44px; object-fit: cover;">
                                    <div>
                                        <div class="fw-bold text-truncate" style="max-width: 220px;" title="{{ $product->name }}">
                                            {{ $product->name }}
                                        </div>
                                        <small class="text-muted">{{ $product->brand->name ?? '-' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $product->category->name ?? '-' }}</td>
                            <td class="text-center font-monospace fs-6 fw-bold">
                                {{ $product->quantity }}
                            </td>
                            <td class="text-center">
                                @if($product->quantity == 0)
                                    <span class="badge bg-danger px-3 py-2">
                                        <i class="fa-solid fa-circle-exclamation me-1"></i> Hết hàng
                                    </span>
                                @elseif($product->quantity <= 5)
                                    <span class="badge bg-warning text-dark px-3 py-2">
                                        <i class="fa-solid fa-triangle-exclamation me-1"></i> Sắp hết hàng
                                    </span>
                                @else
                                    <span class="badge bg-success-subtle text-success px-3 py-2">
                                        <i class="fa-solid fa-check me-1"></i> Bình thường
                                    </span>
                                @endif
                            </td>
                            <td class="text-center font-monospace fw-bold">{{ $product->sold }}</td>
                            <td class="text-end px-3">
                                <form action="{{ route('admin.inventory.quick-update', $product->id) }}" method="POST" class="d-inline-flex align-items-center justify-content-end gap-1">
                                    @csrf
                                    @method('PUT')
                                    <input type="number" name="quantity" class="form-control form-control-sm text-center font-monospace" value="{{ $product->quantity }}" min="0" style="width: 80px;" required>
                                    <button type="submit" class="btn btn-sm btn-outline-primary" title="Lưu số lượng">
                                        <i class="fa-solid fa-check"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">Không có sản phẩm nào.</td>
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
