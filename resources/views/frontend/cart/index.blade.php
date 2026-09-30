@extends('layouts.app')

@section('title', 'Giỏ Hàng Của Bạn - Sport Store')

@section('content')
<div class="container py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-muted">Trang chủ</a></li>
            <li class="breadcrumb-item active text-dark fw-semibold" aria-current="page">Giỏ hàng</li>
        </ol>
    </nav>

    <h3 class="fw-extrabold mb-4"><i class="fa-solid fa-cart-shopping me-2 text-primary"></i> Giỏ Hàng Của Bạn</h3>

    @if(!empty($cart) && count($cart) > 0)
        <div class="row g-4">
            <!-- Cart Items List -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-3">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col" class="py-3 px-4">Sản phẩm</th>
                                    <th scope="col" class="py-3 text-center">Đơn giá</th>
                                    <th scope="col" class="py-3 text-center">Số lượng</th>
                                    <th scope="col" class="py-3 text-end px-4">Thành tiền</th>
                                    <th scope="col" class="py-3 text-center" style="width: 50px;"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cart as $item)
                                    <tr>
                                        <!-- Product Info -->
                                        <td class="py-3 px-4">
                                            <div class="d-flex align-items-center">
                                                <img src="{{ asset($item['thumbnail'] ?? 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=200') }}" 
                                                     alt="{{ $item['name'] }}" 
                                                     class="rounded-3 me-3 border" 
                                                     style="width: 70px; height: 70px; object-fit: cover;">
                                                <div>
                                                    <h6 class="fw-bold mb-1">
                                                        <a href="{{ route('products.show', $item['slug']) }}" class="text-dark text-decoration-none">
                                                            {{ $item['name'] }}
                                                        </a>
                                                    </h6>
                                                    @if(!empty($item['variant_text']))
                                                        <span class="badge bg-light text-secondary border small">{{ $item['variant_text'] }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>

                                        <!-- Unit Price -->
                                        <td class="text-center fw-semibold text-secondary">
                                            {{ number_format($item['price'], 0, ',', '.') }} ₫
                                        </td>

                                        <!-- Quantity -->
                                        <td class="text-center" style="width: 150px;">
                                            <form action="{{ route('cart.update') }}" method="POST" class="d-flex align-items-center justify-content-center">
                                                @csrf
                                                <input type="hidden" name="row_id" value="{{ $item['row_id'] }}">
                                                <div class="input-group input-group-sm" style="width: 110px;">
                                                    <button class="btn btn-outline-secondary" type="submit" name="quantity" value="{{ max(1, $item['quantity'] - 1) }}" {{ $item['quantity'] <= 1 ? 'disabled' : '' }}>-</button>
                                                    <input type="text" class="form-control text-center fw-bold bg-white" value="{{ $item['quantity'] }}" readonly>
                                                    <button class="btn btn-outline-secondary" type="submit" name="quantity" value="{{ $item['quantity'] + 1 }}" {{ $item['quantity'] >= $item['max_stock'] ? 'disabled' : '' }}>+</button>
                                                </div>
                                            </form>
                                            <small class="text-muted d-block mt-1" style="font-size: 0.7rem;">Kho còn: {{ $item['max_stock'] }}</small>
                                        </td>

                                        <!-- Subtotal -->
                                        <td class="text-end px-4 fw-bold text-primary">
                                            {{ number_format($item['subtotal'], 0, ',', '.') }} ₫
                                        </td>

                                        <!-- Remove Item -->
                                        <td class="text-center pe-3">
                                            <form action="{{ route('cart.remove') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="row_id" value="{{ $item['row_id'] }}">
                                                <button type="submit" class="btn btn-link text-danger p-0" title="Xóa khỏi giỏ">
                                                    <i class="fa-regular fa-trash-can fs-5"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Clear Cart & Back to Shop Buttons -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <a href="{{ route('products.index') }}" class="btn btn-outline-dark rounded-pill px-4">
                        <i class="fa-solid fa-arrow-left me-2"></i> Tiếp tục mua sắm
                    </a>

                    <form action="{{ route('cart.clear') }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa toàn bộ giỏ hàng?');">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger rounded-pill px-3">
                            <i class="fa-solid fa-trash-can me-1"></i> Xóa toàn bộ giỏ
                        </button>
                    </form>
                </div>
            </div>

            <!-- Order Summary & Voucher Box -->
            <div class="col-lg-4">
                <!-- Voucher Input -->
                <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
                    <h6 class="fw-bold mb-3"><i class="fa-solid fa-ticket me-2 text-primary"></i> Mã Giảm Giá</h6>
                    @if($coupon)
                        <div class="alert alert-success d-flex justify-content-between align-items-center mb-0 py-2 px-3">
                            <div>
                                <strong>{{ $coupon['code'] }}</strong>
                                <span class="badge bg-success ms-1">Đã áp dụng</span>
                            </div>
                            <form action="{{ route('coupon.remove') }}" method="POST" class="m-0">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-link text-danger p-0 fw-bold">Gỡ bỏ</button>
                            </form>
                        </div>
                    @else
                        <form action="{{ route('coupon.apply') }}" method="POST" class="d-flex gap-2">
                            @csrf
                            <input type="text" name="coupon_code" class="form-control text-uppercase" placeholder="Nhập mã voucher (SPORT10)" required>
                            <button type="submit" class="btn btn-outline-primary fw-bold text-nowrap">Áp dụng</button>
                        </form>
                    @endif
                </div>

                <!-- Cost Summary -->
                <div class="card border-0 shadow-sm rounded-4 p-4">
                    <h5 class="fw-bold mb-4">Tóm Tắt Đơn Hàng</h5>

                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Tạm tính:</span>
                        <span class="fw-bold">{{ number_format($subtotal, 0, ',', '.') }} ₫</span>
                    </div>

                    @if($discount > 0)
                        <div class="d-flex justify-content-between mb-2 text-success">
                            <span>Giảm giá ({{ $coupon['code'] ?? '' }}):</span>
                            <span class="fw-bold">-{{ number_format($discount, 0, ',', '.') }} ₫</span>
                        </div>
                    @endif

                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">Phí vận chuyển:</span>
                        <span class="fw-bold">
                            @if($shippingFee == 0)
                                <span class="text-success">Miễn phí</span>
                            @else
                                {{ number_format($shippingFee, 0, ',', '.') }} ₫
                            @endif
                        </span>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between mb-4 align-items-baseline">
                        <span class="fw-bold fs-5">Tổng tiền:</span>
                        <span class="fw-extrabold fs-3 text-primary">{{ number_format($total, 0, ',', '.') }} ₫</span>
                    </div>

                    @auth
                        <a href="{{ route('checkout') }}" class="btn btn-primary w-100 py-3 rounded-pill fw-bold fs-6 shadow">
                            Tiến Hành Thanh Toán <i class="fa-solid fa-arrow-right ms-2"></i>
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-warning w-100 py-3 rounded-pill fw-bold fs-6 text-dark shadow">
                            <i class="fa-solid fa-lock me-2"></i> Đăng nhập để thanh toán
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    @else
        <!-- Empty Cart State -->
        <div class="card border-0 shadow-sm rounded-4 text-center py-5">
            <div class="card-body">
                <i class="fa-solid fa-cart-shopping text-muted mb-3" style="font-size: 4rem;"></i>
                <h4 class="fw-bold mb-2">Giỏ hàng của bạn đang trống!</h4>
                <p class="text-muted mb-4">Hãy khám phá hàng trăm dụng cụ và trang phục thể thao chính hãng tại cửa hàng của chúng tôi.</p>
                <a href="{{ route('products.index') }}" class="btn btn-primary rounded-pill px-5 py-2 fw-bold">
                    Mua Sắm Ngay
                </a>
            </div>
        </div>
    @endif
</div>
@endsection
