@extends('layouts.app')

@section('title', 'Thanh Toán Đơn Hàng - Sport Store')

@section('content')
<div class="container py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-muted">Trang chủ</a></li>
            <li class="breadcrumb-item"><a href="{{ route('cart.index') }}" class="text-decoration-none text-muted">Giỏ hàng</a></li>
            <li class="breadcrumb-item active text-dark fw-semibold" aria-current="page">Thanh toán</li>
        </ol>
    </nav>

    <h3 class="fw-extrabold mb-4"><i class="fa-solid fa-credit-card me-2 text-primary"></i> Tiến Hành Đặt Hàng</h3>

    <form action="{{ route('checkout.process') }}" method="POST" id="checkoutForm">
        @csrf

        <div class="row g-4">
            <!-- Left Column: Shipping & Payment Information -->
            <div class="col-lg-7">
                <!-- Saved Addresses Selector (if any) -->
                @if($addresses->count() > 0)
                    <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
                        <h5 class="fw-bold mb-3"><i class="fa-solid fa-map-location-dot me-2 text-primary"></i> Địa Chỉ Đã Lưu</h5>
                        <div class="row g-3">
                            @foreach($addresses as $addr)
                                <div class="col-md-6">
                                    <div class="border rounded-3 p-3 cursor-pointer h-100 position-relative js-saved-address {{ $addr->id === ($defaultAddress->id ?? null) ? 'border-primary bg-primary-subtle' : '' }}" 
                                         onclick="fillAddress('{{ addslashes($addr->receiver_name) }}', '{{ $addr->phone }}', '{{ addslashes($addr->full_address) }}', this)">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <strong>{{ $addr->receiver_name }}</strong>
                                            @if($addr->is_default)
                                                <span class="badge bg-primary" style="font-size: 0.65rem;">Mặc định</span>
                                            @endif
                                        </div>
                                        <div class="small text-muted mb-1"><i class="fa-solid fa-phone me-1"></i> {{ $addr->phone }}</div>
                                        <div class="small text-secondary">{{ $addr->full_address }}</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Shipping Address Form -->
                <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
                    <h5 class="fw-bold mb-3"><i class="fa-solid fa-truck me-2 text-primary"></i> Thông Tin Nhận Hàng</h5>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Họ tên người nhận <span class="text-danger">*</span></label>
                            <input type="text" name="customer_name" id="customer_name" class="form-control @error('customer_name') is-invalid @enderror" value="{{ old('customer_name', $defaultAddress->receiver_name ?? $user->name) }}" required>
                            @error('customer_name')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Số điện thoại <span class="text-danger">*</span></label>
                            <input type="text" name="customer_phone" id="customer_phone" class="form-control @error('customer_phone') is-invalid @enderror" value="{{ old('customer_phone', $defaultAddress->phone ?? $user->phone) }}" required>
                            @error('customer_phone')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Địa chỉ Email <span class="text-danger">*</span></label>
                        <input type="email" name="customer_email" class="form-control @error('customer_email') is-invalid @enderror" value="{{ old('customer_email', $user->email) }}" required>
                        @error('customer_email')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Địa chỉ giao hàng chi tiết <span class="text-danger">*</span></label>
                        <textarea name="shipping_address" id="shipping_address" class="form-control @error('shipping_address') is-invalid @enderror" rows="3" placeholder="Số nhà, tên đường, phường/xã, quận/huyện, tỉnh/thành phố..." required>{{ old('shipping_address', $defaultAddress->full_address ?? '') }}</textarea>
                        @error('shipping_address')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-0">
                        <label class="form-label fw-semibold">Ghi chú đơn hàng (Tùy chọn)</label>
                        <textarea name="note" class="form-control" rows="2" placeholder="Ví dụ: Giao giờ hành chính, gọi trước khi đến...">{{ old('note') }}</textarea>
                    </div>
                </div>

                <!-- Payment Method Selection -->
                <div class="card border-0 shadow-sm rounded-4 p-4">
                    <h5 class="fw-bold mb-3"><i class="fa-solid fa-wallet me-2 text-primary"></i> Phương Thức Thanh Toán</h5>

                    <div class="d-flex flex-column gap-3">
                        <!-- Option 1: COD -->
                        <div class="border rounded-3 p-3">
                            <div class="form-check d-flex align-items-center">
                                <input class="form-check-input me-3" type="radio" name="payment_method" id="pay_cod" value="cod" checked>
                                <label class="form-check-label w-100 cursor-pointer" for="pay_cod">
                                    <div class="fw-bold text-dark"><i class="fa-solid fa-money-bill-wave me-2 text-success"></i> Thanh toán tiền mặt khi nhận hàng (COD)</div>
                                    <small class="text-muted">Nhận hàng, kiểm tra sản phẩm thể thao và thanh toán trực tiếp cho bưu tá.</small>
                                </label>
                            </div>
                        </div>

                        <!-- Option 2: Bank Transfer -->
                        <div class="border rounded-3 p-3">
                            <div class="form-check d-flex align-items-center">
                                <input class="form-check-input me-3" type="radio" name="payment_method" id="pay_bank" value="bank_transfer" {{ old('payment_method') === 'bank_transfer' ? 'checked' : '' }}>
                                <label class="form-check-label w-100 cursor-pointer" for="pay_bank">
                                    <div class="fw-bold text-dark"><i class="fa-solid fa-building-columns me-2 text-primary"></i> Chuyển khoản ngân hàng 24/7</div>
                                    <small class="text-muted">Chuyển khoản qua số tài khoản Vietcombank chính thức kèm mã đơn hàng.</small>
                                </label>
                            </div>
                        </div>

                        <!-- Option 3: VNPay -->
                        <div class="border rounded-3 p-3">
                            <div class="form-check d-flex align-items-center">
                                <input class="form-check-input me-3" type="radio" name="payment_method" id="pay_vnpay" value="vnpay" {{ old('payment_method') === 'vnpay' ? 'checked' : '' }}>
                                <label class="form-check-label w-100 cursor-pointer" for="pay_vnpay">
                                    <div class="fw-bold text-dark"><i class="fa-solid fa-qrcode me-2 text-danger"></i> Ví điện tử / Cổng thanh toán VNPay QR</div>
                                    <small class="text-muted">Quét mã VNPay QR qua ứng dụng ngân hàng hoặc ví điện tử tức thì.</small>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Order Items & Summary -->
            <div class="col-lg-5">
                <div class="card border-0 shadow-sm rounded-4 p-4 sticky-top" style="top: 90px;">
                    <h5 class="fw-bold mb-3">Đơn Hàng Của Bạn ({{ count($cart) }} món)</h5>

                    <!-- Item list scroll -->
                    <div class="d-flex flex-column gap-3 mb-4" style="max-height: 320px; overflow-y: auto;">
                        @foreach($cart as $item)
                            <div class="d-flex align-items-center justify-content-between border-bottom pb-3">
                                <div class="d-flex align-items-center">
                                    <div class="position-relative me-3">
                                        <img src="{{ asset($item['thumbnail'] ?? 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=100') }}" 
                                             alt="{{ $item['name'] }}" 
                                             class="rounded-3 border" 
                                             style="width: 55px; height: 55px; object-fit: cover;">
                                        <span class="badge bg-secondary position-absolute top-0 start-100 translate-middle rounded-pill">
                                            {{ $item['quantity'] }}
                                        </span>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold mb-0 text-truncate" style="max-width: 170px;" title="{{ $item['name'] }}">
                                            {{ $item['name'] }}
                                        </h6>
                                        @if(!empty($item['variant_text']))
                                            <small class="text-muted d-block">{{ $item['variant_text'] }}</small>
                                        @endif
                                    </div>
                                </div>
                                <span class="fw-bold text-dark">{{ number_format($item['subtotal'], 0, ',', '.') }} ₫</span>
                            </div>
                        @endforeach
                    </div>

                    <!-- Financial Summary -->
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
                        <span class="fw-bold fs-5">Tổng thanh toán:</span>
                        <span class="fw-extrabold fs-3 text-primary">{{ number_format($total, 0, ',', '.') }} ₫</span>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-3 rounded-pill fw-bold fs-6 shadow">
                        <i class="fa-solid fa-lock me-2"></i> Xác Nhận Đặt Hàng
                    </button>

                    <p class="text-center text-muted small mt-3 mb-0">
                        <i class="fa-solid fa-shield-check text-success me-1"></i> Thông tin đặt hàng của bạn được bảo mật tuyệt đối.
                    </p>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    function fillAddress(name, phone, address, el) {
        document.getElementById('customer_name').value = name;
        document.getElementById('customer_phone').value = phone;
        document.getElementById('shipping_address').value = address;

        document.querySelectorAll('.js-saved-address').forEach(item => {
            item.classList.remove('border-primary', 'bg-primary-subtle');
        });
        el.classList.add('border-primary', 'bg-primary-subtle');
    }
</script>
@endpush
