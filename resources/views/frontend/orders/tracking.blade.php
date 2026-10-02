@extends('layouts.app')

@section('title', 'Tra Cứu Đơn Hàng & Vận Đơn GHN - Sport Store')

@section('meta_description', 'Tra cứu hành trình vận chuyển đơn hàng Sport Store liên kết thời gian thực với Giao Hàng Nhanh (GHN).')

@section('content')
<div class="container py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-muted">Trang chủ</a></li>
            <li class="breadcrumb-item active text-dark fw-semibold" aria-current="page">Tra cứu đơn hàng</li>
        </ol>
    </nav>

    <!-- Header Banner -->
    <div class="bg-gradient text-white rounded-4 p-4 p-md-5 mb-5 shadow-sm position-relative overflow-hidden" 
         style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);">
        <div class="row align-items-center position-relative" style="z-index: 2;">
            <div class="col-lg-8">
                <span class="badge bg-warning text-dark px-3 py-2 fw-bold text-uppercase mb-3">
                    <i class="fa-solid fa-truck-fast me-1"></i> Tích Hợp GHN Real-time
                </span>
                <h2 class="display-6 fw-extrabold text-white mb-2">Tra Cứu Tiến Độ Đơn Hàng</h2>
                <p class="text-light lead mb-0" style="opacity: 0.85;">
                    Nhập mã đơn hàng và số điện thoại của bạn để theo dõi hành trình đóng gói và vận chuyển trực tiếp.
                </p>
            </div>
            <div class="col-lg-4 text-center text-lg-end mt-4 mt-lg-0 d-none d-lg-block">
                <i class="fa-solid fa-magnifying-glass-location text-warning" style="font-size: 5.5rem; opacity: 0.9;"></i>
            </div>
        </div>
    </div>

    <!-- Search Form Card -->
    <div class="card border-0 shadow-sm rounded-4 p-4 mb-5" style="margin-top: -30px; position: relative; z-index: 3;">
        <form action="{{ route('orders.tracking.search') }}" method="POST">
            @csrf
            <div class="row g-3 align-items-end">
                <div class="col-md-6">
                    <label for="order_code" class="form-label fw-bold text-dark">
                        <i class="fa-solid fa-barcode text-primary me-1"></i> Mã đơn hàng <span class="text-danger">*</span>
                    </label>
                    <input type="text" class="form-control form-control-lg rounded-pill @error('order_code') is-invalid @enderror" 
                           id="order_code" name="order_code" value="{{ old('order_code', $code ?? '') }}" 
                           placeholder="Ví dụ: SPORT-XXXXXXXX" required autofocus>
                    @error('order_code')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4">
                    <label for="phone" class="form-label fw-bold text-dark">
                        <i class="fa-solid fa-phone text-primary me-1"></i> Số điện thoại đặt hàng (Tùy chọn)
                    </label>
                    <input type="text" class="form-control form-control-lg rounded-pill" 
                           id="phone" name="phone" value="{{ old('phone', $phone ?? '') }}" 
                           placeholder="09xx xxx xxx">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary btn-lg rounded-pill w-100 fw-bold shadow">
                        <i class="fa-solid fa-magnifying-glass me-1"></i> Tra Cứu
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Tracking Results Section -->
    @if(!empty($error))
        <div class="alert alert-warning border-0 rounded-4 shadow-sm p-4 text-center my-4">
            <div class="rounded-circle bg-warning bg-opacity-25 text-warning d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                <i class="fa-solid fa-triangle-exclamation fs-3"></i>
            </div>
            <h5 class="fw-bold mb-2">Không Tìm Thấy Đơn Hàng</h5>
            <p class="text-muted mb-3">{{ $error }}</p>
            <div class="d-flex justify-content-center gap-2">
                <a href="{{ route('contact') }}" class="btn btn-outline-secondary rounded-pill px-4">
                    <i class="fa-solid fa-headset me-1"></i> Liên hệ tổng đài 1900 8888
                </a>
            </div>
        </div>
    @elseif(!empty($order))
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-5">
            <!-- Order Header -->
            <div class="card-header bg-white py-3 px-4 border-bottom d-flex flex-wrap justify-content-between align-items-center gap-3">
                <div>
                    <span class="text-muted small">Mã đơn hàng:</span>
                    <h5 class="fw-extrabold text-primary mb-0 font-monospace">#{{ $order->order_code }}</h5>
                    <small class="text-muted">
                        <i class="fa-regular fa-clock me-1"></i> Đặt lúc: {{ $order->created_at->format('d/m/Y H:i') }}
                    </small>
                </div>
                <div class="d-flex align-items-center gap-2">
                    @php
                        $statusBadge = match($order->status) {
                            'pending' => ['bg' => 'bg-secondary', 'text' => 'Chờ xử lý'],
                            'confirmed' => ['bg' => 'bg-info text-white', 'text' => 'Đã xác nhận'],
                            'preparing' => ['bg' => 'bg-warning text-dark', 'text' => 'Đang đóng gói'],
                            'shipping', 'delivering' => ['bg' => 'bg-primary text-white', 'text' => 'Đang giao hàng'],
                            'completed', 'delivered' => ['bg' => 'bg-success text-white', 'text' => 'Giao thành công'],
                            'cancelled' => ['bg' => 'bg-danger text-white', 'text' => 'Đã hủy'],
                            default => ['bg' => 'bg-secondary', 'text' => ucfirst($order->status)]
                        };
                    @endphp
                    <span class="badge {{ $statusBadge['bg'] }} px-3 py-2 fs-6 rounded-pill">
                        {{ $statusBadge['text'] }}
                    </span>
                    <button class="btn btn-outline-secondary btn-sm rounded-pill px-3" onclick="window.print()">
                        <i class="fa-solid fa-print me-1"></i> In
                    </button>
                </div>
            </div>

            <div class="card-body p-4">
                <!-- Status Stepper Timeline -->
                @if($order->status === 'cancelled')
                    <div class="alert alert-danger rounded-4 p-3 mb-4">
                        <i class="fa-solid fa-circle-xmark me-2"></i>
                        <strong>Đơn hàng đã bị hủy.</strong> 
                        Lý do: {{ $order->cancel_reason ?? 'Khách hàng yêu cầu hủy hoặc lỗi thanh toán.' }}
                    </div>
                @else
                    <div class="py-4 mb-4 border-bottom">
                        <h6 class="fw-bold mb-4 text-secondary text-uppercase" style="font-size: 0.8rem; letter-spacing: 0.5px;">
                            <i class="fa-solid fa-timeline me-1 text-primary"></i> Tiến Trình Xử Lý Đơn Hàng
                        </h6>
                        <div class="position-relative m-4">
                            <div class="progress" style="height: 4px;">
                                @php
                                    $progressPercent = match($currentStep) {
                                        1 => 10,
                                        2 => 35,
                                        3 => 60,
                                        4 => 85,
                                        5 => 100,
                                        default => 10
                                    };
                                @endphp
                                <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $progressPercent }}%;"></div>
                            </div>
                            <div class="d-flex justify-content-between position-absolute top-50 start-0 w-100 translate-middle-y">
                                <!-- Step 1 -->
                                <div class="text-center">
                                    <div class="rounded-circle {{ $currentStep >= 1 ? 'bg-primary text-white' : 'bg-light border text-muted' }} d-inline-flex align-items-center justify-content-center shadow-sm" style="width: 38px; height: 38px;">
                                        <i class="fa-solid fa-receipt"></i>
                                    </div>
                                    <div class="small fw-bold mt-2 {{ $currentStep >= 1 ? 'text-primary' : 'text-muted' }}">Đặt hàng</div>
                                </div>
                                <!-- Step 2 -->
                                <div class="text-center">
                                    <div class="rounded-circle {{ $currentStep >= 2 ? 'bg-primary text-white' : 'bg-light border text-muted' }} d-inline-flex align-items-center justify-content-center shadow-sm" style="width: 38px; height: 38px;">
                                        <i class="fa-solid fa-circle-check"></i>
                                    </div>
                                    <div class="small fw-bold mt-2 {{ $currentStep >= 2 ? 'text-primary' : 'text-muted' }}">Đã xác nhận</div>
                                </div>
                                <!-- Step 3 -->
                                <div class="text-center">
                                    <div class="rounded-circle {{ $currentStep >= 3 ? 'bg-primary text-white' : 'bg-light border text-muted' }} d-inline-flex align-items-center justify-content-center shadow-sm" style="width: 38px; height: 38px;">
                                        <i class="fa-solid fa-box-open"></i>
                                    </div>
                                    <div class="small fw-bold mt-2 {{ $currentStep >= 3 ? 'text-primary' : 'text-muted' }}">Đang đóng gói</div>
                                </div>
                                <!-- Step 4 -->
                                <div class="text-center">
                                    <div class="rounded-circle {{ $currentStep >= 4 ? 'bg-primary text-white' : 'bg-light border text-muted' }} d-inline-flex align-items-center justify-content-center shadow-sm" style="width: 38px; height: 38px;">
                                        <i class="fa-solid fa-truck-fast"></i>
                                    </div>
                                    <div class="small fw-bold mt-2 {{ $currentStep >= 4 ? 'text-primary' : 'text-muted' }}">Đang giao (GHN)</div>
                                </div>
                                <!-- Step 5 -->
                                <div class="text-center">
                                    <div class="rounded-circle {{ $currentStep >= 5 ? 'bg-success text-white' : 'bg-light border text-muted' }} d-inline-flex align-items-center justify-content-center shadow-sm" style="width: 38px; height: 38px;">
                                        <i class="fa-solid fa-house-chimney-check"></i>
                                    </div>
                                    <div class="small fw-bold mt-2 {{ $currentStep >= 5 ? 'text-success' : 'text-muted' }}">Giao thành công</div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- GHN Realtime Tracking Card -->
                @if($order->ghn_order_code)
                    <div class="card bg-primary-subtle border-primary-subtle rounded-4 p-3 mb-4">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                            <div class="d-flex align-items-center gap-3">
                                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center flex-shrink-0" style="width: 48px; height: 48px;">
                                    <i class="fa-solid fa-truck-ramp-box fs-4"></i>
                                </div>
                                <div>
                                    <span class="text-muted small d-block">Mã vận đơn Giao Hàng Nhanh (GHN):</span>
                                    <span class="fw-bold fs-5 font-monospace text-primary">{{ $order->ghn_order_code }}</span>
                                    <span class="badge bg-primary ms-2">{{ ucfirst($order->shipping_status ?? 'Đang trung chuyển') }}</span>
                                </div>
                            </div>
                            <div>
                                <a href="https://donhang.ghn.vn/?order_code={{ $order->ghn_order_code }}" 
                                   target="_blank" 
                                   rel="noopener noreferrer" 
                                   class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm">
                                    <i class="fa-solid fa-arrow-up-right-from-square me-1"></i> Tra cứu tại cổng GHN
                                </a>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="row g-4 mb-4">
                    <!-- Delivery Recipient Info -->
                    <div class="col-md-6">
                        <div class="card h-100 border rounded-4 p-3">
                            <h6 class="fw-bold mb-3 text-secondary">
                                <i class="fa-solid fa-location-dot text-primary me-2"></i> Thông Tin Giao Hàng
                            </h6>
                            <p class="mb-1"><strong>Họ tên:</strong> {{ $order->customer_name }}</p>
                            <p class="mb-1"><strong>Số điện thoại:</strong> {{ $order->customer_phone }}</p>
                            @if($order->customer_email)
                                <p class="mb-1"><strong>Email:</strong> {{ $order->customer_email }}</p>
                            @endif
                            <p class="mb-1"><strong>Địa chỉ:</strong> {{ $order->shipping_address }}</p>
                            @if($order->note)
                                <p class="mb-0 text-muted fst-italic"><strong>Ghi chú:</strong> "{{ $order->note }}"</p>
                            @endif
                        </div>
                    </div>

                    <!-- Payment Information -->
                    <div class="col-md-6">
                        <div class="card h-100 border rounded-4 p-3">
                            <h6 class="fw-bold mb-3 text-secondary">
                                <i class="fa-solid fa-credit-card text-primary me-2"></i> Thanh Toán & Đơn Hàng
                            </h6>
                            <p class="mb-1">
                                <strong>Phương thức:</strong> 
                                @if($order->payment_method === 'momo')
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle"><i class="fa-solid fa-wallet me-1"></i> Ví Điện Tử MoMo</span>
                                @elseif($order->payment_method === 'bank_transfer')
                                    <span class="badge bg-info-subtle text-info border border-info-subtle"><i class="fa-solid fa-building-columns me-1"></i> Chuyển Khoản Ngân Hàng</span>
                                @else
                                    <span class="badge bg-secondary-subtle text-dark border"><i class="fa-solid fa-money-bill-wave me-1"></i> Tiền Mặt (COD)</span>
                                @endif
                            </p>
                            <p class="mb-1">
                                <strong>Trạng thái thanh toán:</strong>
                                @if($order->payment_status === 'paid')
                                    <span class="badge bg-success-subtle text-success border border-success-subtle"><i class="fa-solid fa-check me-1"></i> Đã thanh toán</span>
                                @else
                                    <span class="badge bg-warning-subtle text-warning border border-warning-subtle"><i class="fa-solid fa-hourglass-half me-1"></i> Chưa thanh toán</span>
                                @endif
                            </p>
                            @if($order->coupon_code)
                                <p class="mb-0 text-success">
                                    <strong>Mã giảm giá áp dụng:</strong> <span class="badge bg-success">{{ $order->coupon_code }}</span>
                                </p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Products Table -->
                <h6 class="fw-bold mb-3 text-secondary">
                    <i class="fa-solid fa-basket-shopping text-primary me-2"></i> Sản Phẩm Trong Đơn Hàng ({{ $order->items->count() }})
                </h6>
                <div class="table-responsive mb-4">
                    <table class="table align-middle border rounded-4 overflow-hidden mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="py-3 px-4">Sản phẩm</th>
                                <th class="py-3 text-center">Đơn giá</th>
                                <th class="py-3 text-center">Số lượng</th>
                                <th class="py-3 text-end px-4">Thành tiền</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                                <tr>
                                    <td class="py-3 px-4">
                                        <div class="d-flex align-items-center">
                                            @if($item->product_image)
                                                <img src="{{ asset($item->product_image) }}" alt="{{ $item->product_name }}" class="rounded-3 me-3 border" style="width: 55px; height: 55px; object-fit: cover;">
                                            @elseif($item->product && $item->product->thumbnail)
                                                <img src="{{ asset($item->product->thumbnail) }}" alt="{{ $item->product_name }}" class="rounded-3 me-3 border" style="width: 55px; height: 55px; object-fit: cover;">
                                            @else
                                                <div class="rounded-3 me-3 border bg-light d-flex align-items-center justify-content-center text-muted" style="width: 55px; height: 55px;">
                                                    <i class="fa-solid fa-image"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <h6 class="fw-semibold mb-1 text-dark">{{ $item->product_name }}</h6>
                                                @if($item->size || $item->color)
                                                    <small class="text-muted">
                                                        @if($item->size) Size: <strong>{{ $item->size }}</strong> @endif
                                                        @if($item->color) Màu: <strong>{{ $item->color }}</strong> @endif
                                                    </small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">{{ number_format($item->price, 0, ',', '.') }} ₫</td>
                                    <td class="text-center fw-bold">{{ $item->quantity }}</td>
                                    <td class="text-end px-4 fw-bold text-primary">{{ number_format($item->subtotal, 0, ',', '.') }} ₫</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <td colspan="3" class="text-end py-2">Tạm tính:</td>
                                <td class="text-end px-4 py-2">{{ number_format($order->subtotal, 0, ',', '.') }} ₫</td>
                            </tr>
                            @if($order->discount_amount > 0)
                                <tr>
                                    <td colspan="3" class="text-end py-2 text-success">Giảm giá:</td>
                                    <td class="text-end px-4 py-2 text-success">-{{ number_format($order->discount_amount, 0, ',', '.') }} ₫</td>
                                </tr>
                            @endif
                            <tr>
                                <td colspan="3" class="text-end py-2">Phí vận chuyển (GHN):</td>
                                <td class="text-end px-4 py-2">{{ number_format($order->shipping_fee, 0, ',', '.') }} ₫</td>
                            </tr>
                            <tr class="fw-bold fs-5">
                                <td colspan="3" class="text-end py-3 text-dark">Tổng thanh toán:</td>
                                <td class="text-end px-4 py-3 text-primary">{{ number_format($order->total_amount, 0, ',', '.') }} ₫</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <!-- Footer Action Buttons -->
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                    <a href="{{ route('products.index') }}" class="btn btn-outline-dark rounded-pill px-4">
                        <i class="fa-solid fa-arrow-left me-2"></i> Tiếp tục mua sắm
                    </a>
                    @auth
                        @if($order->user_id === auth()->id())
                            <a href="{{ route('account.orders.invoice', $order) }}" class="btn btn-outline-primary rounded-pill px-4" target="_blank">
                                <i class="fa-solid fa-file-invoice me-2"></i> Xem hóa đơn chi tiết
                            </a>
                        @endif
                    @endauth
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
