<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Quản Trị Hệ Thống') - Sport Store Admin</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>

    <style>
        :root {
            --sidebar-bg: #0f172a;
            --sidebar-hover: #1e293b;
            --sidebar-active: #ff5722;
            --sidebar-width: 260px;
            --admin-primary: #ff5722;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f1f5f9;
            color: #334155;
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* Admin Sidebar */
        .admin-sidebar {
            width: var(--sidebar-width);
            background-color: var(--sidebar-bg);
            min-height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1040;
            transition: all 0.3s;
        }

        .admin-sidebar .brand {
            padding: 1.25rem 1.5rem;
            font-size: 1.3rem;
            font-weight: 800;
            color: #ffffff;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            display: flex;
            align-items: center;
        }

        .admin-sidebar .nav-link {
            color: #94a3b8;
            font-weight: 500;
            padding: 0.75rem 1.5rem;
            display: flex;
            align-items: center;
            border-left: 3px solid transparent;
            transition: all 0.2s;
        }

        .admin-sidebar .nav-link i {
            width: 24px;
            font-size: 1.1rem;
            margin-right: 0.75rem;
        }

        .admin-sidebar .nav-link:hover {
            color: #ffffff;
            background-color: var(--sidebar-hover);
        }

        .admin-sidebar .nav-link.active {
            color: #ffffff;
            background-color: rgba(255, 87, 34, 0.15);
            border-left-color: var(--sidebar-active);
            font-weight: 700;
        }

        .sidebar-section-title {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05rem;
            color: #64748b;
            padding: 1rem 1.5rem 0.25rem;
            font-weight: 700;
        }

        /* Admin Content Area */
        .admin-main {
            margin-left: var(--sidebar-width);
            padding: 1.5rem 2rem;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .admin-topbar {
            background: #ffffff;
            border-radius: 12px;
            padding: 0.85rem 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.03);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.04);
            margin-bottom: 1.5rem;
        }

        .card-header {
            background-color: #ffffff;
            border-bottom: 1px solid #f1f5f9;
            padding: 1rem 1.25rem;
            font-weight: 700;
        }

        .table th {
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.04rem;
            color: #64748b;
            font-weight: 700;
            background-color: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
        }

        .table td {
            vertical-align: middle;
            font-size: 0.9rem;
        }

        .btn-action {
            width: 32px;
            height: 32px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
        }

        @media (max-width: 991px) {
            .admin-sidebar {
                transform: translateX(-100%);
            }
            .admin-sidebar.show {
                transform: translateX(0);
            }
            .admin-main {
                margin-left: 0;
                padding: 1rem;
            }
        }
    </style>
    @stack('styles')
</head>
<body>
    <!-- Sidebar -->
    <aside class="admin-sidebar" id="adminSidebar">
        <div class="brand">
            <i class="fa-solid fa-bolt text-warning me-2 fs-4"></i>
            SPORT<span style="color: var(--admin-primary)">ADMIN</span>
        </div>

        <div class="py-3">
            <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fa-solid fa-gauge-high"></i> Dashboard
            </a>

            <div class="sidebar-section-title">Quản Lý Sản Phẩm</div>
            <a href="{{ route('admin.categories.index') }}" class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                <i class="fa-solid fa-layer-group"></i> Danh Mục
            </a>
            <a href="{{ route('admin.brands.index') }}" class="nav-link {{ request()->routeIs('admin.brands.*') ? 'active' : '' }}">
                <i class="fa-solid fa-tags"></i> Thương Hiệu
            </a>
            <a href="{{ route('admin.products.index') }}" class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                <i class="fa-solid fa-box-open"></i> Sản Phẩm
            </a>
            <a href="{{ route('admin.inventory.index') }}" class="nav-link {{ request()->routeIs('admin.inventory.*') ? 'active' : '' }}">
                <i class="fa-solid fa-warehouse"></i> Quản Lý Tồn Kho
            </a>

            <div class="sidebar-section-title">Bán Hàng & Khách Hàng</div>
            <a href="{{ route('admin.orders.index') }}" class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                <i class="fa-solid fa-cart-shopping"></i> Đơn Hàng
            </a>
            <a href="{{ route('admin.ghn.index') }}" class="nav-link {{ request()->routeIs('admin.ghn.*') ? 'active' : '' }}">
                <i class="fa-solid fa-truck-fast"></i> Đồng Bộ GHN
            </a>
            <a href="{{ route('admin.customers.index') }}" class="nav-link {{ request()->routeIs('admin.customers.*') ? 'active' : '' }}">
                <i class="fa-solid fa-users"></i> Khách Hàng
            </a>
            <a href="{{ route('admin.coupons.index') }}" class="nav-link {{ request()->routeIs('admin.coupons.*') ? 'active' : '' }}">
                <i class="fa-solid fa-ticket"></i> Mã Giảm Giá
            </a>
            <a href="{{ route('admin.reviews.index') }}" class="nav-link {{ request()->routeIs('admin.reviews.*') ? 'active' : '' }}">
                <i class="fa-solid fa-star"></i> Đánh Giá Sản Phẩm
            </a>
            <a href="{{ route('admin.contacts.index') }}" class="nav-link {{ request()->routeIs('admin.contacts.*') ? 'active' : '' }}">
                <i class="fa-solid fa-envelope-open-text"></i> Liên Hệ & Phản Hồi
                @php
                    $unreadContactsCount = \App\Models\Contact::where('status', 'unread')->count();
                @endphp
                @if($unreadContactsCount > 0)
                    <span class="badge bg-danger ms-auto">{{ $unreadContactsCount }}</span>
                @endif
            </a>
            <a href="{{ route('admin.chats.index') }}" class="nav-link {{ request()->routeIs('admin.chats.*') ? 'active' : '' }}">
                <i class="fa-solid fa-comments"></i> Live Chat Khách Hàng
                @php
                    $unreadChatsCount = \App\Models\ChatMessage::where('sender_type', 'user')->where('is_read', false)->count();
                @endphp
                @if($unreadChatsCount > 0)
                    <span class="badge bg-danger ms-auto">{{ $unreadChatsCount }}</span>
                @endif
            </a>

            <div class="sidebar-section-title">Hệ Thống & Báo Cáo</div>
            <a href="{{ route('admin.banners.index') }}" class="nav-link {{ request()->routeIs('admin.banners.*') ? 'active' : '' }}">
                <i class="fa-solid fa-image"></i> Banners Quảng Cáo
            </a>
            <a href="{{ route('admin.reports.index') }}" class="nav-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
                <i class="fa-solid fa-chart-line"></i> Báo Cáo Doanh Thu
            </a>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="admin-main">
        <!-- Topbar -->
        <div class="admin-topbar">
            <div class="d-flex align-items-center">
                <button class="btn btn-sm btn-light d-lg-none me-2" onclick="document.getElementById('adminSidebar').classList.toggle('show')">
                    <i class="fa-solid fa-bars"></i>
                </button>
                <a href="{{ route('home') }}" target="_blank" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                    <i class="fa-solid fa-arrow-up-right-from-square me-1"></i> Xem Trang Bán Hàng
                </a>
            </div>

            <div class="d-flex align-items-center gap-3">
                <div class="d-flex align-items-center">
                    <div class="rounded-circle bg-danger text-white fw-bold d-flex align-items-center justify-content-center me-2" style="width: 36px; height: 36px;">
                        A
                    </div>
                    <div>
                        <div class="fw-bold small">{{ auth()->user()->name }}</div>
                        <span class="badge bg-danger" style="font-size: 0.65rem;">ADMINISTRATOR</span>
                    </div>
                </div>

                <form action="{{ route('logout') }}" method="POST" class="m-0">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Đăng xuất">
                        <i class="fa-solid fa-right-from-bracket"></i>
                    </button>
                </form>
            </div>
        </div>

        <!-- Page Content -->
        <div class="flex-grow-1">
            @yield('content')
        </div>

        <footer class="mt-4 pt-3 border-top text-center text-muted small">
            © {{ date('Y') }} <strong>Sport Store E-commerce</strong> - Trang Quản Trị Hệ Thống.
        </footer>
    </main>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Thành công!',
                    text: "{{ session('success') }}",
                    timer: 2000,
                    showConfirmButton: false,
                    toast: true,
                    position: 'top-end'
                });
            @endif

            @if(session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi',
                    text: "{{ session('error') }}",
                    confirmButtonColor: '#ff5722'
                });
            @endif

            // Confirm delete helper
            document.querySelectorAll('.form-delete-confirm').forEach(form => {
                form.addEventListener('submit', function (e) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Bạn có chắc chắn?',
                        text: "Hành động này sẽ xóa dữ liệu và không thể hoàn tác!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#ef4444',
                        cancelButtonColor: '#64748b',
                        confirmButtonText: 'Đồng ý xóa',
                        cancelButtonText: 'Hủy'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        });
    </script>
    @stack('scripts')
</body>
</html>
