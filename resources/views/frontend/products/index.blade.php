@extends('layouts.app')

@section('title', 'Danh Sách Dụng Cụ Thể Thao - Sport Store')

@section('content')
<div class="container py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-muted">Trang chủ</a></li>
            <li class="breadcrumb-item active text-dark fw-semibold" aria-current="page">Sản phẩm</li>
            @if(request('category'))
                <li class="breadcrumb-item active text-primary fw-semibold">{{ request('category') }}</li>
            @endif
        </ol>
    </nav>

    <!-- Search keyword notification -->
    @if(request('q'))
        <div class="alert alert-light border shadow-sm mb-4 d-flex justify-content-between align-items-center">
            <div>
                <i class="fa-solid fa-magnifying-glass text-primary me-2"></i>
                Kết quả tìm kiếm cho từ khóa: <strong>"{{ request('q') }}"</strong> ({{ $products->total() }} sản phẩm)
            </div>
            <a href="{{ route('products.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill">Xóa tìm kiếm</a>
        </div>
    @endif

    <div class="row g-4">
        <!-- Sidebar Filters -->
        <div class="col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 p-4 sticky-top" style="top: 90px; z-index: 10;">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold mb-0"><i class="fa-solid fa-filter me-2 text-primary"></i> Bộ Lọc</h5>
                    <a href="{{ route('products.index') }}" class="small text-danger text-decoration-none fw-semibold">Xóa tất cả</a>
                </div>

                <form action="{{ route('products.index') }}" method="GET" id="filterForm">
                    @if(request('q'))
                        <input type="hidden" name="q" value="{{ request('q') }}">
                    @endif

                    <!-- Category Filter -->
                    <div class="mb-4">
                        <label class="form-label fw-bold text-dark mb-2">Danh mục</label>
                        <div class="d-flex flex-column gap-1" style="max-height: 200px; overflow-y: auto;">
                            @foreach($categories as $cat)
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="category" value="{{ $cat->slug }}" id="cat_{{ $cat->id }}" {{ request('category') == $cat->slug ? 'checked' : '' }} onchange="document.getElementById('filterForm').submit()">
                                    <label class="form-check-label d-flex justify-content-between align-items-center small" for="cat_{{ $cat->id }}">
                                        <span>{{ $cat->name }}</span>
                                        <span class="text-muted">({{ $cat->products_count }})</span>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Brand Filter -->
                    <div class="mb-4">
                        <label class="form-label fw-bold text-dark mb-2">Thương hiệu</label>
                        <div class="d-flex flex-column gap-1" style="max-height: 180px; overflow-y: auto;">
                            @foreach($brands as $brand)
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="brand" value="{{ $brand->slug }}" id="brand_{{ $brand->id }}" {{ request('brand') == $brand->slug ? 'checked' : '' }} onchange="document.getElementById('filterForm').submit()">
                                    <label class="form-check-label d-flex justify-content-between align-items-center small" for="brand_{{ $brand->id }}">
                                        <span>{{ $brand->name }}</span>
                                        <span class="text-muted">({{ $brand->products_count }})</span>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Price Filter -->
                    <div class="mb-4">
                        <label class="form-label fw-bold text-dark mb-2">Khoảng giá (VNĐ)</label>
                        <div class="row g-2 mb-2">
                            <div class="col-6">
                                <input type="number" name="min_price" class="form-control form-control-sm" placeholder="Từ" value="{{ request('min_price') }}">
                            </div>
                            <div class="col-6">
                                <input type="number" name="max_price" class="form-control form-control-sm" placeholder="Đến" value="{{ request('max_price') }}">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-sm btn-outline-primary w-100 rounded-pill">Áp dụng giá</button>
                    </div>

                    <!-- Size Filter -->
                    @if($availableSizes->count() > 0)
                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark mb-2">Kích cỡ / Size</label>
                            <div class="d-flex flex-wrap gap-2">
                                @foreach($availableSizes as $size)
                                    <input type="radio" class="btn-check" name="size" id="size_{{ $size }}" value="{{ $size }}" {{ request('size') == $size ? 'checked' : '' }} onchange="document.getElementById('filterForm').submit()">
                                    <label class="btn btn-outline-secondary btn-sm" for="size_{{ $size }}">{{ $size }}</label>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Color Filter -->
                    @if($availableColors->count() > 0)
                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark mb-2">Màu sắc</label>
                            <div class="d-flex flex-wrap gap-2">
                                @foreach($availableColors as $color)
                                    <input type="radio" class="btn-check" name="color" id="color_{{ $color }}" value="{{ $color }}" {{ request('color') == $color ? 'checked' : '' }} onchange="document.getElementById('filterForm').submit()">
                                    <label class="btn btn-outline-secondary btn-sm" for="color_{{ $color }}">{{ $color }}</label>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Rating Filter -->
                    <div class="mb-3">
                        <label class="form-label fw-bold text-dark mb-2">Đánh giá tối thiểu</label>
                        <select name="rating" class="form-select form-select-sm" onchange="document.getElementById('filterForm').submit()">
                            <option value="">Tất cả đánh giá</option>
                            <option value="5" {{ request('rating') == 5 ? 'selected' : '' }}>5 sao</option>
                            <option value="4" {{ request('rating') == 4 ? 'selected' : '' }}>Từ 4 sao trở lên</option>
                            <option value="3" {{ request('rating') == 3 ? 'selected' : '' }}>Từ 3 sao trở lên</option>
                        </select>
                    </div>
                </form>
            </div>
        </div>

        <!-- Products Grid & Sort Header -->
        <div class="col-lg-9">
            <!-- Sort and Total Bar -->
            <div class="card border-0 shadow-sm rounded-4 p-3 mb-4">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                    <div class="text-muted small">
                        Hiển thị <strong>{{ $products->firstItem() ?? 0 }} - {{ $products->lastItem() ?? 0 }}</strong> trên tổng số <strong>{{ $products->total() }}</strong> sản phẩm
                    </div>

                    <div class="d-flex align-items-center gap-2">
                        <label class="small text-muted text-nowrap fw-semibold">Sắp xếp theo:</label>
                        <select class="form-select form-select-sm rounded-pill" style="width: auto;" onchange="let url = new URL(window.location.href); url.searchParams.set('sort', this.value); window.location.href = url.toString();">
                            <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Mới nhất</option>
                            <option value="bestseller" {{ request('sort') == 'bestseller' ? 'selected' : '' }}>Bán chạy nhất</option>
                            <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Giá: Thấp đến cao</option>
                            <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Giá: Cao đến thấp</option>
                            <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>Đánh giá cao nhất</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Products List -->
            @if($products->count() > 0)
                <div class="row g-4 mb-4">
                    @foreach($products as $product)
                        <div class="col-6 col-md-4">
                            @include('frontend.components.product-card', ['product' => $product])
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-5">
                    {{ $products->links('pagination::bootstrap-5') }}
                </div>
            @else
                <!-- Empty State -->
                <div class="card border-0 shadow-sm rounded-4 text-center py-5">
                    <div class="card-body">
                        <i class="fa-solid fa-box-open text-muted mb-3" style="font-size: 4rem;"></i>
                        <h5 class="fw-bold">Không tìm thấy sản phẩm phù hợp.</h5>
                        <p class="text-muted small">Hãy thử điều chỉnh lại bộ lọc hoặc từ khóa tìm kiếm của bạn.</p>
                        <a href="{{ route('products.index') }}" class="btn btn-primary rounded-pill px-4 mt-2">
                            Xem tất cả sản phẩm
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
