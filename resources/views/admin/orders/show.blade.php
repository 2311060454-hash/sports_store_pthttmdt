@extends('layouts.admin')

@section('title', 'Chi Tiết Đơn Hàng #' . $order->order_code)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Chi Tiết Đơn Hàng #{{ $order->order_code }}</h4>
        <p class="text-muted small mb-0">Đặt lúc {{ $order->created_at->format('d/m/Y H:i:s') }}</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.orders.invoice', $order->id) }}" target="_blank" class="btn btn-outline-dark rounded-pill px-3">
            <i class="fa-solid fa-print me-1"></i> In hóa đơn
        </a>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary rounded-pill px-3">
            <i class="fa-solid fa-arrow-left me-1"></i> Danh sách đơn
        </a>
    </div>
</div>

<div class="row g-4">
    <!-- Left Column: Order Items & Progress Tracker -->
    <div class="col-lg-8">
        <!-- Progress Tracker -->
        <div class="card mb-4 p-4">
            <h5 class="fw-bold mb-4"><i class="fa-solid fa-timeline me-2 text-primary"></i> Tiến Trình Xử Lý Đơn</h5>

            @if($order->status === 'cancelled')
                <div class="alert alert-danger mb-0">
                    <h6 class="fw-bold mb-1"><i class="fa-solid fa-ban me-1"></i> Đơn Hàng Đã Hủy</h6>
                    <small>Lý do: {{ $order->cancel_reason ?? 'Không có lý do chi tiết.' }}</small>
                </div>
            @else
                <div class="d-flex justify-content-between text-center position-relative">
                    <div style="flex: 1;">
                        <div class="rounded-circle {{ $currentStep >= 1 ? 'bg-success text-white' : 'bg-light text-muted' }} d-inline-flex align-items-center justify-content-center shadow-sm" style="width: 36px; height: 36px;">
                            <i class="fa-solid fa-file-invoice"></i>
                        </div>
                        <div class="small fw-bold mt-2">Chờ xử lý</div>
                    </div>
                    <div style="flex: 1;">
                        <div class="rounded-circle {{ $currentStep >= 2 ? 'bg-success text-white' : 'bg-light text-muted border' }} d-inline-flex align-items-center justify-content-center shadow-sm" style="width: 36px; height: 36px;">
                            <i class="fa-solid fa-check"></i>
                        </div>
                        <div class="small fw-bold mt-2">Xác nhận</div>
                    </div>
                    <div style="flex: 1;">
                        <div class="rounded-circle {{ $currentStep >= 3 ? 'bg-success text-white' : 'bg-light text-muted border' }} d-inline-flex align-items-center justify-content-center shadow-sm" style="width: 36px; height: 36px;">
                            <i class="fa-solid fa-box"></i>
                        </div>
                        <div class="small fw-bold mt-2">Chuẩn bị</div>
                    </div>
                    <div style="flex: 1;">
                        <div class="rounded-circle {{ $currentStep >= 4 ? 'bg-success text-white' : 'bg-light text-muted border' }} d-inline-flex align-items-center justify-content-center shadow-sm" style="width: 36px; height: 36px;">
                            <i class="fa-solid fa-truck"></i>
                        </div>
                        <div class="small fw-bold mt-2">Đang giao</div>
                    </div>
                    <div style="flex: 1;">
                        <div class="rounded-circle {{ $currentStep >= 5 ? 'bg-success text-white' : 'bg-light text-muted border' }} d-inline-flex align-items-center justify-content-center shadow-sm" style="width: 36px; height: 36px;">
                            <i class="fa-solid fa-flag-checkered"></i>
                        </div>
                        <div class="small fw-bold mt-2">Hoàn thành</div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Ordered Items Table -->
        <div class="card mb-4">
            <div class="card-header bg-white py-3">
                <h5 class="fw-bold mb-0">Sản Phẩm Trong Đơn</h5>
            </div>
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="px-3">Sản phẩm</th>
                            <th class="text-center">Đơn giá</th>
                            <th class="text-center">Số lượng</th>
                            <th class="text-end px-3">Thành tiền</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                            <tr>
                                <td class="px-3">
                                    <div class="d-flex align-items-center">
                                        <img src="{{ asset($item->product_image ?? 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=100') }}" 
                                             class="rounded-3 border me-3" 
                                             style="width: 50px; height: 50px; object-fit: cover;">
                                        <div>
                                            <div class="fw-bold">{{ $item->product_name }}</div>
                                            @if($item->size || $item->color)
                                                <small class="text-muted">
                                                    @if($item->size) Size: {{ $item->size }} @endif
                                                    @if($item->color) Màu: {{ $item->color }} @endif
                                                </small>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">{{ number_format($item->price, 0, ',', '.') }} ₫</td>
                                <td class="text-center font-monospace">{{ $item->quantity }}</td>
                                <td class="text-end px-3 fw-bold text-primary">{{ number_format($item->subtotal, 0, ',', '.') }} ₫</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Cost Calculations Footer -->
            <div class="card-footer bg-white p-4">
                <div class="row justify-content-end">
                    <div class="col-md-6">
                        <div class="d-flex justify-content-between mb-2 small">
                            <span class="text-muted">Tạm tính:</span>
                            <span class="fw-semibold">{{ number_format($order->subtotal, 0, ',', '.') }} ₫</span>
                        </div>
                        @if($order->discount_amount > 0)
                            <div class="d-flex justify-content-between mb-2 small text-success">
                                <span>Giảm giá ({{ $order->coupon_code }}):</span>
                                <span class="fw-semibold">-{{ number_format($order->discount_amount, 0, ',', '.') }} ₫</span>
                            </div>
                        @endif
                        <div class="d-flex justify-content-between mb-2 small">
                            <span class="text-muted">Phí vận chuyển:</span>
                            <span class="fw-semibold">{{ number_format($order->shipping_fee, 0, ',', '.') }} ₫</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between align-items-baseline">
                            <span class="fw-bold">Tổng thanh toán:</span>
                            <span class="fw-extrabold text-primary fs-4">{{ number_format($order->total_amount, 0, ',', '.') }} ₫</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column: Status Update & Customer Details -->
    <div class="col-lg-4">
        <!-- Status Progression Form -->
        <div class="card mb-4">
            <div class="card-header bg-white py-3">
                <h5 class="fw-bold mb-0">Cập Nhật Trạng Thái Đơn</h5>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('admin.orders.status', $order->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label fw-bold">Trạng thái hiện tại:</label>
                        <div>
                            <span class="badge {{ $order->status_badge_class }} fs-6">{{ $order->status_label }}</span>
                        </div>
                    </div>

                    @if($order->status !== 'completed' && $order->status !== 'cancelled')
                        <div class="mb-3">
                            <label class="form-label fw-bold">Chuyển sang trạng thái tiếp theo:</label>
                            <select name="status" class="form-select" required>
                                @if($order->status === 'pending')
                                    <option value="confirmed">Đã xác nhận</option>
                                    <option value="cancelled">Hủy đơn hàng</option>
                                @elseif($order->status === 'confirmed')
                                    <option value="preparing">Đang chuẩn bị hàng</option>
                                    <option value="cancelled">Hủy đơn hàng</option>
                                @elseif($order->status === 'preparing')
                                    <option value="shipping">Đang giao hàng</option>
                                    <option value="cancelled">Hủy đơn hàng</option>
                                @elseif($order->status === 'shipping')
                                    <option value="completed">Đã hoàn thành</option>
                                    <option value="cancelled">Hủy đơn hàng</option>
                                @endif
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 rounded-pill fw-bold">
                            <i class="fa-solid fa-arrows-rotate me-1"></i> Cập Nhật Ngay
                        </button>
                    @else
                        <div class="alert alert-secondary small mb-0">
                            Đơn hàng đã ở trạng thái kết thúc ({{ $order->status_label }}), không thể chỉnh sửa tiếp.
                        </div>
                    @endif
                </form>
            </div>
        </div>

        <!-- Customer & Shipping Card -->
        <div class="card">
            <div class="card-header bg-white py-3">
                <h5 class="fw-bold mb-0">Thông Tin Khách Hàng</h5>
            </div>
            <div class="card-body p-4">
                <div class="d-flex flex-column gap-2 small">
                    <div><span class="text-muted">Họ tên:</span> <strong>{{ $order->customer_name }}</strong></div>
                    <div><span class="text-muted">Số điện thoại:</span> <strong>{{ $order->customer_phone }}</strong></div>
                    <div><span class="text-muted">Email:</span> <strong>{{ $order->customer_email }}</strong></div>
                    <div><span class="text-muted">Địa chỉ nhận hàng:</span> <strong>{{ $order->shipping_address }}</strong></div>
                    @if($order->note)
                        <div><span class="text-muted">Ghi chú:</span> <em>{{ $order->note }}</em></div>
                    @endif
                    <hr>
                    <div><span class="text-muted">Hình thức thanh toán:</span> <strong>{{ $order->payment_method_label }}</strong></div>
                    <div>
                        <span class="text-muted">Tình trạng thanh toán:</span>
                        <span class="badge {{ $order->payment_status === 'paid' ? 'bg-success' : 'bg-secondary' }}">
                            {{ $order->payment_status === 'paid' ? 'Đã thu tiền' : 'Chưa thanh toán' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
