@extends('layouts.app')

@section('content')
<div class="container py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-muted">Trang chủ</a></li>
            <li class="breadcrumb-item active text-dark fw-semibold" aria-current="page">Tài khoản</li>
            @yield('account_breadcrumb')
        </ol>
    </nav>

    <div class="row g-4">
        <!-- Account Sidebar -->
        <div class="col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 p-4 text-center mb-3">
                <div class="position-relative d-inline-block mx-auto mb-3">
                    @if(auth()->user()->avatar)
                        <img src="{{ asset(auth()->user()->avatar) }}" alt="{{ auth()->user()->name }}" class="rounded-circle border" style="width: 85px; height: 85px; object-fit: cover;">
                    @else
                        <div class="rounded-circle bg-warning-subtle text-warning fw-bold d-flex align-items-center justify-content-center mx-auto" style="width: 85px; height: 85px; font-size: 2rem;">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                    @endif
                </div>
                <h5 class="fw-bold mb-1">{{ auth()->user()->name }}</h5>
                <span class="text-muted small mb-2 d-block">{{ auth()->user()->email }}</span>
                <span class="badge bg-success-subtle text-success px-3 py-1 rounded-pill" style="width: fit-content; margin: 0 auto;">Khách Hàng Thân Thiết</span>
            </div>

            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="list-group list-group-flush py-2">
                    <a href="{{ route('account.index') }}" class="list-group-item list-group-item-action py-3 px-4 border-0 d-flex align-items-center {{ request()->routeIs('account.index') ? 'active bg-primary text-white' : '' }}">
                        <i class="fa-solid fa-gauge me-3" style="width: 20px;"></i> Tổng quan tài khoản
                    </a>
                    <a href="{{ route('account.profile') }}" class="list-group-item list-group-item-action py-3 px-4 border-0 d-flex align-items-center {{ request()->routeIs('account.profile') ? 'active bg-primary text-white' : '' }}">
                        <i class="fa-solid fa-user-pen me-3" style="width: 20px;"></i> Thông tin cá nhân
                    </a>
                    <a href="{{ route('account.orders') }}" class="list-group-item list-group-item-action py-3 px-4 border-0 d-flex align-items-center {{ request()->routeIs('account.orders*') ? 'active bg-primary text-white' : '' }}">
                        <i class="fa-solid fa-box-open me-3" style="width: 20px;"></i> Đơn hàng của tôi
                    </a>
                    <a href="{{ route('account.addresses') }}" class="list-group-item list-group-item-action py-3 px-4 border-0 d-flex align-items-center {{ request()->routeIs('account.addresses') ? 'active bg-primary text-white' : '' }}">
                        <i class="fa-solid fa-map-location-dot me-3" style="width: 20px;"></i> Sổ địa chỉ nhận hàng
                    </a>
                    <a href="{{ route('account.wishlist') }}" class="list-group-item list-group-item-action py-3 px-4 border-0 d-flex align-items-center {{ request()->routeIs('account.wishlist') ? 'active bg-primary text-white' : '' }}">
                        <i class="fa-solid fa-heart me-3" style="width: 20px;"></i> Sản phẩm yêu thích
                    </a>
                    <a href="{{ route('account.password') }}" class="list-group-item list-group-item-action py-3 px-4 border-0 d-flex align-items-center {{ request()->routeIs('account.password') ? 'active bg-primary text-white' : '' }}">
                        <i class="fa-solid fa-shield-halved me-3" style="width: 20px;"></i> Đổi mật khẩu
                    </a>
                    <form action="{{ route('logout') }}" method="POST" class="m-0">
                        @csrf
                        <button type="submit" class="list-group-item list-group-item-action py-3 px-4 border-0 text-danger d-flex align-items-center w-100">
                            <i class="fa-solid fa-right-from-bracket me-3" style="width: 20px;"></i> Đăng xuất
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Account Main Sub-View -->
        <div class="col-lg-9">
            @yield('account_content')
        </div>
    </div>
</div>
@endsection
