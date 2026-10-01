<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Sport Store - Cửa Hàng Dụng Cụ Thể Thao Cao Cấp')</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <style>
        :root {
            --primary: #ff5722;
            --primary-hover: #e64a19;
            --secondary: #0f172a;
            --dark-blue: #1e293b;
            --light-bg: #f8fafc;
            --accent-green: #10b981;
            --border-color: #e2e8f0;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--light-bg);
            color: #334155;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* Topbar */
        .topbar {
            background-color: var(--secondary);
            font-size: 0.85rem;
            color: #94a3b8;
        }

        /* Header Sticky */
        .navbar-main {
            background-color: #ffffff;
            box-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.06);
            transition: all 0.3s ease;
        }

        .sticky-top {
            z-index: 1030;
        }

        .navbar-brand {
            font-weight: 800;
            font-size: 1.6rem;
            color: var(--secondary);
            letter-spacing: -0.5px;
        }

        .navbar-brand span {
            color: var(--primary);
        }

        .nav-link {
            font-weight: 600;
            color: #475569;
            padding: 0.5rem 1rem !important;
            transition: color 0.2s ease;
        }

        .nav-link:hover, .nav-link.active {
            color: var(--primary);
        }

        .search-box .form-control {
            border-radius: 50px 0 0 50px;
            border: 1px solid var(--border-color);
            padding: 0.55rem 1.25rem;
        }

        .search-box .btn {
            border-radius: 0 50px 50px 0;
            background-color: var(--primary);
            border-color: var(--primary);
            color: #ffffff;
            padding: 0.55rem 1.25rem;
        }

        .search-box .btn:hover {
            background-color: var(--primary-hover);
        }

        /* Buttons & Badges */
        .btn-primary {
            background-color: var(--primary);
            border-color: var(--primary);
            font-weight: 600;
            padding: 0.5rem 1.25rem;
            border-radius: 8px;
            transition: all 0.2s;
        }

        .btn-primary:hover {
            background-color: var(--primary-hover);
            border-color: var(--primary-hover);
            transform: translateY(-1px);
        }

        .badge-cart, .badge-wishlist {
            position: absolute;
            top: -5px;
            right: -8px;
            font-size: 0.7rem;
            padding: 0.25em 0.5em;
            border-radius: 50rem;
        }

        /* Product Card */
        .product-card {
            background: #fff;
            border: 1px solid var(--border-color);
            border-radius: 12px;
            overflow: hidden;
            transition: all 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
            position: relative;
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 28px rgba(0, 0, 0, 0.08);
            border-color: #cbd5e1;
        }

        .product-badge-discount {
            position: absolute;
            top: 10px;
            left: 10px;
            background: #ef4444;
            color: #fff;
            font-size: 0.75rem;
            font-weight: 700;
            padding: 4px 8px;
            border-radius: 6px;
            z-index: 2;
        }

        .product-card .img-wrapper {
            position: relative;
            padding-top: 100%;
            overflow: hidden;
            background-color: #f1f5f9;
        }

        .product-card .img-wrapper img {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.4s ease;
        }

        .product-card:hover .img-wrapper img {
            transform: scale(1.06);
        }

        .btn-wishlist-toggle {
            position: absolute;
            top: 10px;
            right: 10px;
            background: rgba(255, 255, 255, 0.9);
            border: none;
            width: 34px;
            height: 34px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #64748b;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
            transition: all 0.2s;
            z-index: 2;
        }

        .btn-wishlist-toggle:hover, .btn-wishlist-toggle.active {
            color: #ef4444;
            background: #ffffff;
            transform: scale(1.1);
        }

        /* Price display */
        .price-current {
            font-size: 1.15rem;
            font-weight: 800;
            color: var(--primary);
        }

        .price-old {
            font-size: 0.85rem;
            text-decoration: line-through;
            color: #94a3b8;
            margin-left: 6px;
        }

        /* Footer */
        footer {
            background-color: var(--secondary);
            color: #94a3b8;
            margin-top: auto;
        }

        footer h6 {
            color: #ffffff;
            font-weight: 700;
            margin-bottom: 1.25rem;
        }

        footer a {
            color: #94a3b8;
            text-decoration: none;
            transition: color 0.2s;
        }

        footer a:hover {
            color: var(--primary);
        }

        .policy-card {
            background: #ffffff;
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 1.5rem;
            transition: transform 0.2s;
        }

        .policy-card:hover {
            transform: translateY(-3px);
        }

        .policy-icon {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            background: rgba(255, 87, 34, 0.1);
            color: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
            margin-right: 1rem;
        }
    </style>
    @stack('styles')
</head>
<body>
    @php
        $cartService = app(\App\Services\CartService::class);
        $cartCount = $cartService->getItemsCount();
        $wishlistCount = auth()->check() ? auth()->user()->wishlists()->count() : 0;
    @endphp

    <!-- Topbar -->
    <div class="topbar py-2 d-none d-md-block">
        <div class="container d-flex justify-content-between align-items-center">
            <div>
                <span class="me-3"><i class="fa-solid fa-phone me-1 text-warning"></i> Hotline: <strong>1900 8888</strong></span>
                <span><i class="fa-solid fa-envelope me-1 text-warning"></i> support@sportstore.com</span>
            </div>
            <div>
                <span class="me-3"><i class="fa-solid fa-truck-fast me-1 text-success"></i> Miễn phí vận chuyển từ 500.000đ</span>
                @if(auth()->check() && auth()->user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="badge bg-danger text-white text-decoration-none px-2 py-1">
                        <i class="fa-solid fa-shield-halved me-1"></i> Trang Quản Trị
                    </a>
                @endif
            </div>
        </div>
    </div>

    <!-- Main Navigation Bar (Sticky) -->
    <header class="sticky-top">
        <nav class="navbar navbar-expand-lg navbar-main">
            <div class="container">
                <!-- Brand Logo -->
                <a class="navbar-brand d-flex align-items-center" href="{{ route('home') }}">
                    <i class="fa-solid fa-bolt me-2 text-warning fs-3"></i>
                    SPORT<span>STORE</span>
                </a>

                <!-- Mobile Toggler -->
                <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarContent">
                    <!-- Nav Links -->
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-3">
                        <li class="nav-link-item">
                            <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Trang chủ</a>
                        </li>
                        <li class="nav-link-item">
                            <a class="nav-link {{ request()->routeIs('products.index') && !request()->filled('on_sale') ? 'active' : '' }}" href="{{ route('products.index') }}">Sản phẩm</a>
                        </li>
                        <li class="nav-link-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">Danh mục</a>
                            <ul class="dropdown-menu border-0 shadow">
                                @foreach(\App\Models\Category::where('status', 'active')->get() as $cat)
                                    <li><a class="dropdown-item py-2" href="{{ route('products.index', ['category' => $cat->slug]) }}">{{ $cat->name }}</a></li>
                                @endforeach
                            </ul>
                        </li>
                        <li class="nav-link-item">
                            <a class="nav-link text-danger {{ request()->routeIs('promotions') ? 'active fw-bold' : '' }}" href="{{ route('promotions') }}">
                                <i class="fa-solid fa-fire me-1"></i> Khuyến mãi
                            </a>
                        </li>
                        <li class="nav-link-item">
                            <a class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}" href="{{ route('about') }}">Giới thiệu</a>
                        </li>
                        <li class="nav-link-item">
                            <a class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}" href="{{ route('contact') }}">Liên hệ</a>
                        </li>
                    </ul>

                    <!-- Search Box -->
                    <form action="{{ route('search') }}" method="GET" class="d-flex search-box me-3 my-2 my-lg-0">
                        <input class="form-control" type="search" name="q" value="{{ request('q') }}" placeholder="Tìm vợt, giày, bóng, áo..." required>
                        <button class="btn" type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
                    </form>

                    <!-- User Actions -->
                    <div class="d-flex align-items-center gap-3">
                        <!-- Wishlist -->
                        @auth
                            <a href="{{ route('account.wishlist') }}" class="btn btn-outline-secondary position-relative rounded-circle p-2" title="Yêu thích" style="width: 40px; height: 40px;">
                                <i class="fa-solid fa-heart text-danger"></i>
                                <span class="badge bg-danger badge-wishlist js-wishlist-count">{{ $wishlistCount }}</span>
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-outline-secondary position-relative rounded-circle p-2" title="Yêu thích" style="width: 40px; height: 40px;">
                                <i class="fa-regular fa-heart"></i>
                            </a>
                        @endauth

                        <!-- Cart Button -->
                        <a href="{{ route('cart.index') }}" class="btn btn-primary position-relative rounded-pill px-3 py-2 d-flex align-items-center" title="Giỏ hàng">
                            <i class="fa-solid fa-cart-shopping me-2"></i>
                            <span class="d-none d-sm-inline">Giỏ hàng</span>
                            <span class="badge bg-dark ms-2 js-cart-count">{{ $cartCount }}</span>
                        </a>

                        <!-- Account Dropdown -->
                        @auth
                            <div class="dropdown">
                                <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle text-dark fw-semibold" data-bs-toggle="dropdown">
                                    @if(auth()->user()->avatar)
                                        <img src="{{ asset(auth()->user()->avatar) }}" alt="{{ auth()->user()->name }}" class="rounded-circle me-2" style="width: 36px; height: 36px; object-fit: cover;">
                                    @else
                                        <div class="rounded-circle bg-warning text-dark fw-bold d-flex align-items-center justify-content-center me-2" style="width: 36px; height: 36px;">
                                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                        </div>
                                    @endif
                                    <span class="d-none d-lg-inline">{{ Str::limit(auth()->user()->name, 12) }}</span>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2">
                                    <li class="px-3 py-2 border-bottom">
                                        <div class="fw-bold">{{ auth()->user()->name }}</div>
                                        <small class="text-muted">{{ auth()->user()->email }}</small>
                                    </li>
                                    @if(auth()->user()->isAdmin())
                                        <li><a class="dropdown-item py-2 text-danger fw-bold" href="{{ route('admin.dashboard') }}"><i class="fa-solid fa-gauge me-2"></i> Quản trị website</a></li>
                                        <li><hr class="dropdown-divider"></li>
                                    @endif
                                    <li><a class="dropdown-item py-2" href="{{ route('account.index') }}"><i class="fa-solid fa-user me-2 text-muted"></i> Trang tài khoản</a></li>
                                    <li><a class="dropdown-item py-2" href="{{ route('account.orders') }}"><i class="fa-solid fa-box-archive me-2 text-muted"></i> Đơn hàng của tôi</a></li>
                                    <li><a class="dropdown-item py-2" href="{{ route('account.addresses') }}"><i class="fa-solid fa-map-location-dot me-2 text-muted"></i> Sổ địa chỉ</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form action="{{ route('logout') }}" method="POST">
                                            @csrf
                                            <button type="submit" class="dropdown-item py-2 text-danger"><i class="fa-solid fa-right-from-bracket me-2"></i> Đăng xuất</button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-outline-dark rounded-pill px-3 py-2">
                                <i class="fa-solid fa-user me-1"></i> Đăng nhập
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>
    </header>

    <!-- Main Content -->
    <main class="flex-grow-1">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="pt-5 pb-4 mt-5">
        <div class="container">
            <div class="row g-4 pb-4 border-bottom border-secondary">
                <div class="col-lg-4 col-md-6">
                    <h5 class="text-white fw-bold mb-3 d-flex align-items-center">
                        <i class="fa-solid fa-bolt text-warning me-2"></i> SPORT<span class="text-primary">STORE</span>
                    </h5>
                    <p class="text-secondary small">
                        Hệ thống phân phối dụng cụ thể thao chính hãng hàng đầu Việt Nam: bóng đá, cầu lông, tennis, bóng rổ, gym & fitness từ các thương hiệu hàng đầu thế giới Nike, Adidas, Yonex, Wilson...
                    </p>
                    <div class="d-flex gap-3 fs-5 mt-3">
                        <a href="#"><i class="fa-brands fa-facebook"></i></a>
                        <a href="#"><i class="fa-brands fa-youtube"></i></a>
                        <a href="#"><i class="fa-brands fa-tiktok"></i></a>
                        <a href="#"><i class="fa-brands fa-instagram"></i></a>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6">
                    <h6>Về Chúng Tôi</h6>
                    <ul class="list-unstyled small d-flex flex-column gap-2">
                        <li><a href="{{ route('about') }}">Giới thiệu cửa hàng</a></li>
                        <li><a href="{{ route('promotions') }}">Khuyến mãi & Voucher</a></li>
                        <li><a href="{{ route('contact') }}">Hệ thống showroom</a></li>
                        <li><a href="{{ route('contact') }}">Liên hệ hỗ trợ</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h6>Chính Sách & Hỗ Trợ</h6>
                    <ul class="list-unstyled small d-flex flex-column gap-2">
                        <li><a href="{{ route('about') }}">Chính sách giao hàng toàn quốc</a></li>
                        <li><a href="{{ route('about') }}">Chính sách đổi trả trong 30 ngày</a></li>
                        <li><a href="{{ route('about') }}">Bảo hành chính hãng 12 tháng</a></li>
                        <li><a href="{{ route('contact') }}">Hỗ trợ tư vấn chọn size</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h6>Thông Tin Liên Hệ</h6>
                    <ul class="list-unstyled small text-secondary d-flex flex-column gap-2">
                        <li><i class="fa-solid fa-location-dot me-2 text-primary"></i> 123 Đường Thể Thao, Quận 1, TP. Hồ Chí Minh</li>
                        <li><i class="fa-solid fa-phone me-2 text-primary"></i> 1900 8888 (8:00 - 21:00)</li>
                        <li><i class="fa-solid fa-envelope me-2 text-primary"></i> contact@sportstore.com</li>
                        <li><i class="fa-solid fa-clock me-2 text-primary"></i> Thứ 2 - Chủ Nhật (8h00 - 22h00)</li>
                    </ul>
                </div>
            </div>

            <div class="row pt-4 text-center text-md-start align-items-center">
                <div class="col-md-6 small">
                    © {{ date('Y') }} <strong>Sport Store</strong>. Bản quyền thuộc về đề tài Phát Triển Hệ Thống Thương Mại Điện Tử.
                </div>
                <div class="col-md-6 text-md-end mt-2 mt-md-0 small">
                    <span class="me-3"><i class="fa-solid fa-shield-check text-success me-1"></i> Thanh toán an toàn 100%</span>
                    <span><i class="fa-solid fa-truck text-warning me-1"></i> Giao hàng toàn quốc</span>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Global Flash Notification & AJAX Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Flash messages
            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Thành công!',
                    text: "{{ session('success') }}",
                    timer: 2500,
                    showConfirmButton: false,
                    toast: true,
                    position: 'top-end'
                });
            @endif

            @if(session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Thông báo',
                    text: "{{ session('error') }}",
                    confirmButtonColor: '#ff5722',
                    confirmButtonText: 'Đóng'
                });
            @endif

            @if($errors->any())
                Swal.fire({
                    icon: 'warning',
                    title: 'Dữ liệu chưa hợp lệ',
                    html: `{!! implode('<br>', $errors->all()) !!}`,
                    confirmButtonColor: '#ff5722',
                    confirmButtonText: 'Kiểm tra lại'
                });
            @endif

            // Wishlist Toggle AJAX handler
            document.querySelectorAll('.js-btn-wishlist').forEach(button => {
                button.addEventListener('click', function (e) {
                    e.preventDefault();
                    @guest
                        window.location.href = "{{ route('login') }}";
                        return;
                    @endguest

                    const productId = this.dataset.productId;
                    const btn = this;

                    fetch("{{ route('wishlist.toggle') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ product_id: productId })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            if (data.added) {
                                btn.classList.add('active');
                                btn.innerHTML = '<i class="fa-solid fa-heart text-danger"></i>';
                            } else {
                                btn.classList.remove('active');
                                btn.innerHTML = '<i class="fa-regular fa-heart"></i>';
                            }

                            document.querySelectorAll('.js-wishlist-count').forEach(el => {
                                el.textContent = data.wishlist_count;
                            });

                            Swal.fire({
                                icon: 'success',
                                text: data.message,
                                timer: 1500,
                                showConfirmButton: false,
                                toast: true,
                                position: 'top-end'
                            });
                        }
                    })
                    .catch(err => console.error(err));
                });
            });

            // Quick Add-to-Cart AJAX handler
            document.querySelectorAll('.js-quick-add-cart').forEach(button => {
                button.addEventListener('click', function (e) {
                    e.preventDefault();
                    const productId = this.dataset.productId;
                    const btn = this;
                    btn.disabled = true;

                    fetch("{{ route('cart.add') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            product_id: productId,
                            quantity: 1
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        btn.disabled = false;
                        if (data.success) {
                            document.querySelectorAll('.js-cart-count').forEach(el => {
                                el.textContent = data.cart_count;
                            });
                            Swal.fire({
                                icon: 'success',
                                title: 'Thêm vào giỏ thành công!',
                                text: data.message,
                                timer: 1800,
                                showConfirmButton: false,
                                toast: true,
                                position: 'top-end'
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Không thể thêm',
                                text: data.message,
                                confirmButtonColor: '#ff5722'
                            });
                        }
                    })
                    .catch(err => {
                        btn.disabled = false;
                        console.error(err);
                    });
                });
            });
        });
    </script>
    @stack('scripts')
</body>
</html>
