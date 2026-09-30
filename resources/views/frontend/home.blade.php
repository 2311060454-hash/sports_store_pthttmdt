@extends('layouts.app')

@section('title', 'Sport Store - Cửa Hàng Dụng Cụ Thể Thao Hàng Đầu')

@section('content')
<!-- Hero Banner Carousel -->
<section class="banner-carousel mb-5">
    <div id="heroCarousel" class="carousel slide carousel-fade shadow-sm" data-bs-ride="carousel">
        <div class="carousel-indicators">
            @forelse($banners as $index => $banner)
                <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="{{ $index }}" class="{{ $index === 0 ? 'active' : '' }}"></button>
            @empty
                <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active"></button>
                <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1"></button>
                <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2"></button>
            @endforelse
        </div>
        <div class="carousel-inner" style="max-height: 520px; background-color: #0f172a;">
            @forelse($banners as $index => $banner)
                <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                    <img src="{{ asset($banner->image) }}" class="d-block w-100" alt="{{ $banner->title }}" style="object-fit: cover; height: 500px; opacity: 0.85;">
                    <div class="carousel-caption text-start d-flex flex-column justify-content-center h-100" style="bottom: 0; left: 10%; right: 40%;">
                        <span class="badge bg-warning text-dark px-3 py-2 fw-bold text-uppercase mb-3" style="width: fit-content;">Chính Hãng 100%</span>
                        <h1 class="display-4 fw-extrabold text-white mb-3" style="font-weight: 800; text-shadow: 0 2px 10px rgba(0,0,0,0.5);">{{ $banner->title }}</h1>
                        <p class="lead text-light mb-4 d-none d-md-block">Khám phá bộ sưu tập dụng cụ thể thao chuyên nghiệp với ưu đãi lên tới 40% ngay hôm nay.</p>
                        <a href="{{ $banner->link ?? route('products.index') }}" class="btn btn-primary btn-lg rounded-pill px-4 fw-bold shadow" style="width: fit-content;">
                            Mua Ngay <i class="fa-solid fa-arrow-right ms-2"></i>
                        </a>
                    </div>
                </div>
            @empty
                <!-- Default Slider 1 -->
                <div class="carousel-item active">
                    <img src="https://images.unsplash.com/photo-1517649763962-0c623266ddc0?w=1600&auto=format&fit=crop&q=80" class="d-block w-100" alt="Dụng cụ bóng đá" style="object-fit: cover; height: 500px; opacity: 0.8;">
                    <div class="carousel-caption text-start d-flex flex-column justify-content-center h-100" style="bottom: 0; left: 10%; right: 40%;">
                        <span class="badge bg-warning text-dark px-3 py-2 fw-bold text-uppercase mb-3" style="width: fit-content;">Dụng cụ Bóng Đá</span>
                        <h1 class="display-4 text-white mb-3" style="font-weight: 800;">Chinh Phục Mọi Sân Cỏ</h1>
                        <p class="lead text-light mb-4 d-none d-md-block">Giày đá bóng Nike Mercurial & Adidas Predator chuẩn thi đấu quốc tế.</p>
                        <a href="{{ route('products.index', ['category' => 'bong-da']) }}" class="btn btn-primary btn-lg rounded-pill px-4 fw-bold shadow" style="width: fit-content;">
                            Mua Ngay <i class="fa-solid fa-arrow-right ms-2"></i>
                        </a>
                    </div>
                </div>
                <!-- Default Slider 2 -->
                <div class="carousel-item">
                    <img src="https://images.unsplash.com/photo-1626224583764-f87db24ac4ea?w=1600&auto=format&fit=crop&q=80" class="d-block w-100" alt="Vợt cầu lông" style="object-fit: cover; height: 500px; opacity: 0.8;">
                    <div class="carousel-caption text-start d-flex flex-column justify-content-center h-100" style="bottom: 0; left: 10%; right: 40%;">
                        <span class="badge bg-info text-white px-3 py-2 fw-bold text-uppercase mb-3" style="width: fit-content;">Vợt Cầu Lông Cao Cấp</span>
                        <h1 class="display-4 text-white mb-3" style="font-weight: 800;">Tốc Độ & Uy Lực Tối Đa</h1>
                        <p class="lead text-light mb-4 d-none d-md-block">Dòng vợt Yonex Astrox và Li-Ning chính hãng dành cho tay vợt cừ khôi.</p>
                        <a href="{{ route('products.index', ['category' => 'cau-long']) }}" class="btn btn-primary btn-lg rounded-pill px-4 fw-bold shadow" style="width: fit-content;">
                            Khám Phá <i class="fa-solid fa-arrow-right ms-2"></i>
                        </a>
                    </div>
                </div>
            @endforelse
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
        </button>
    </div>
</section>

<!-- Policies 4 Cards -->
<section class="container mb-5">
    <div class="row g-4">
        <div class="col-6 col-lg-3">
            <div class="policy-card d-flex align-items-center h-100 shadow-sm">
                <div class="policy-icon"><i class="fa-solid fa-truck-fast"></i></div>
                <div>
                    <h6 class="fw-bold mb-1">Giao Hàng Toàn Quốc</h6>
                    <p class="text-muted small mb-0">Miễn phí cho đơn từ 500k</p>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="policy-card d-flex align-items-center h-100 shadow-sm">
                <div class="policy-icon"><i class="fa-solid fa-rotate-left"></i></div>
                <div>
                    <h6 class="fw-bold mb-1">Đổi Trả Dễ Dàng</h6>
                    <p class="text-muted small mb-0">Hỗ trợ đổi trong 7 ngày</p>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="policy-card d-flex align-items-center h-100 shadow-sm">
                <div class="policy-icon"><i class="fa-solid fa-shield-halved"></i></div>
                <div>
                    <h6 class="fw-bold mb-1">Thanh Toán An Toàn</h6>
                    <p class="text-muted small mb-0">COD, Chuyển khoản, VNPay</p>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="policy-card d-flex align-items-center h-100 shadow-sm">
                <div class="policy-icon"><i class="fa-solid fa-headset"></i></div>
                <div>
                    <h6 class="fw-bold mb-1">Hỗ Trợ 24/7</h6>
                    <p class="text-muted small mb-0">Tư vấn chọn size chuẩn xác</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Featured Categories -->
<section class="container mb-5">
    <div class="d-flex justify-content-between align-items-end mb-4">
        <div>
            <span class="text-primary fw-bold text-uppercase" style="font-size: 0.8rem; letter-spacing: 1px;">Danh Mục Môn Thể Thao</span>
            <h3 class="fw-extrabold mb-0">Danh Mục Nổi Bật</h3>
        </div>
        <a href="{{ route('products.index') }}" class="text-decoration-none fw-bold text-primary small">
            Xem tất cả <i class="fa-solid fa-arrow-right ms-1"></i>
        </a>
    </div>

    <div class="row g-3">
        @foreach($featuredCategories as $cat)
            <div class="col-6 col-md-4 col-lg-3">
                <a href="{{ route('products.index', ['category' => $cat->slug]) }}" class="text-decoration-none text-dark">
                    <div class="card border-0 shadow-sm h-100 rounded-3 overflow-hidden text-center position-relative group-hover-effect">
                        <div style="height: 140px; overflow: hidden; background: #e2e8f0;">
                            <img src="{{ asset($cat->image ?? 'https://images.unsplash.com/photo-1546519638-68e109498ffc?w=400&auto=format&fit=crop&q=60') }}" 
                                 alt="{{ $cat->name }}" 
                                 class="w-100 h-100" 
                                 style="object-fit: cover; transition: transform 0.4s;">
                        </div>
                        <div class="card-body p-3">
                            <h6 class="fw-bold mb-1">{{ $cat->name }}</h6>
                            <span class="text-muted small">{{ $cat->products_count }} sản phẩm</span>
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
    </div>
</section>

<!-- Featured Products -->
<section class="container mb-5">
    <div class="d-flex justify-content-between align-items-end mb-4">
        <div>
            <span class="text-primary fw-bold text-uppercase" style="font-size: 0.8rem; letter-spacing: 1px;">Gợi Ý Hàng Đầu</span>
            <h3 class="fw-extrabold mb-0">Sản Phẩm Nổi Bật</h3>
        </div>
        <a href="{{ route('products.index') }}" class="text-decoration-none fw-bold text-primary small">
            Khám phá thêm <i class="fa-solid fa-arrow-right ms-1"></i>
        </a>
    </div>

    <div class="row g-4">
        @foreach($featuredProducts as $product)
            <div class="col-6 col-md-4 col-lg-3">
                @include('frontend.components.product-card', ['product' => $product])
            </div>
        @endforeach
    </div>
</section>

<!-- Mid-page Promo Banner -->
<section class="container mb-5">
    <div class="card border-0 rounded-4 overflow-hidden text-white shadow" style="background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 100%);">
        <div class="row align-items-center g-0 p-4 p-md-5">
            <div class="col-md-7">
                <span class="badge bg-warning text-dark fw-bold mb-3 px-3 py-2">MÃ GIẢM GIÁ ĐẶC BIỆT</span>
                <h2 class="display-6 fw-bold mb-3">Nhập mã <span class="text-warning">SPORT10</span> để giảm ngay 10%</h2>
                <p class="text-light lead mb-4">Áp dụng cho mọi đơn hàng phụ kiện, giày đá banh và vợt cầu lông từ 300.000đ.</p>
                <a href="{{ route('products.index') }}" class="btn btn-warning rounded-pill px-4 py-2 fw-bold text-dark">
                    Mua Sắm Ngay <i class="fa-solid fa-bolt ms-1"></i>
                </a>
            </div>
            <div class="col-md-5 text-center d-none d-md-block">
                <i class="fa-solid fa-medal text-warning" style="font-size: 8rem; opacity: 0.9;"></i>
            </div>
        </div>
    </div>
</section>

<!-- New Arrivals & Best Sellers Tabs -->
<section class="container mb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-extrabold mb-0">Sản Phẩm Bán Chạy Nhất</h3>
        <a href="{{ route('products.index', ['sort' => 'bestseller']) }}" class="text-decoration-none fw-bold text-primary small">
            Xem tất cả <i class="fa-solid fa-arrow-right ms-1"></i>
        </a>
    </div>

    <div class="row g-4 mb-5">
        @foreach($bestSellers as $product)
            <div class="col-6 col-md-4 col-lg-3">
                @include('frontend.components.product-card', ['product' => $product])
            </div>
        @endforeach
    </div>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-extrabold mb-0">Hàng Mới Về</h3>
        <a href="{{ route('products.index', ['sort' => 'latest']) }}" class="text-decoration-none fw-bold text-primary small">
            Xem tất cả <i class="fa-solid fa-arrow-right ms-1"></i>
        </a>
    </div>

    <div class="row g-4">
        @foreach($newProducts as $product)
            <div class="col-6 col-md-4 col-lg-3">
                @include('frontend.components.product-card', ['product' => $product])
            </div>
        @endforeach
    </div>
</section>

<!-- Featured Brands -->
<section class="container mb-5">
    <div class="text-center mb-4">
        <span class="text-primary fw-bold text-uppercase" style="font-size: 0.8rem; letter-spacing: 1px;">Thương Hiệu Thể Thao Uy Tín</span>
        <h3 class="fw-extrabold">Đồng Hành Cùng Các Thương Hiệu Hàng Đầu</h3>
    </div>

    <div class="row g-3 justify-content-center align-items-center">
        @foreach($brands as $brand)
            <div class="col-4 col-md-3 col-lg-2">
                <a href="{{ route('products.index', ['brand' => $brand->slug]) }}" class="text-decoration-none">
                    <div class="card border-0 shadow-sm p-3 text-center h-100 rounded-3 d-flex align-items-center justify-content-center" style="min-height: 90px;">
                        <h6 class="fw-bold mb-0 text-dark">{{ $brand->name }}</h6>
                    </div>
                </a>
            </div>
        @endforeach
    </div>
</section>
@endsection
