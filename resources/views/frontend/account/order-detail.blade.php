@extends('frontend.account.layout')

@section('title', 'Chi Tiết Đơn Hàng #' . $order->order_code . ' - Sport Store')

@section('account_breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('account.orders') }}" class="text-decoration-none text-muted">Đơn hàng của tôi</a></li>
    <li class="breadcrumb-item active text-primary fw-semibold" aria-current="page">#{{ $order->order_code }}</li>
@endsection

@section('account_content')
<!-- Order Header -->
<div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <h4 class="fw-bold mb-0">Đơn Hàng #{{ $order->order_code }}</h4>
                <span class="badge {{ $order->status_badge_class }} fs-6">{{ $order->status_label }}</span>
            </div>
            <div class="text-muted small">
                Đặt lúc: {{ $order->created_at->format('d/m/Y H:i:s') }}
            </div>
        </div>

        <div class="d-flex flex-wrap gap-2">
            @if($order->payment_method === 'momo' && $order->payment_status !== 'paid' && $order->status !== 'cancelled')
                <a href="{{ route('orders.momo.pay', $order->id) }}" class="btn text-white rounded-pill px-3 shadow-sm fw-bold" style="background-color: #a50064;">
                    <i class="fa-solid fa-wallet me-1"></i> Thanh toán lại qua MoMo
                </a>
            @endif
            <a href="{{ route('account.orders.invoice', $order->id) }}" target="_blank" class="btn btn-outline-secondary rounded-pill px-3">
                <i class="fa-solid fa-print me-1"></i> In hóa đơn
            </a>
            @if($order->canBeCancelled())
                <button type="button" class="btn btn-outline-danger rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#cancelOrderModal">
                    <i class="fa-solid fa-ban me-1"></i> Hủy đơn hàng này
                </button>
            @endif
        </div>
    </div>
</div>

<!-- Order Timeline Tracker -->
<div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 mb-4">
    <h5 class="fw-bold mb-4"><i class="fa-solid fa-timeline me-2 text-primary"></i> Tiến Trình Đơn Hàng</h5>

    @if($order->status === 'cancelled')
        <div class="alert alert-danger rounded-3 p-4 mb-0">
            <h6 class="fw-bold mb-2"><i class="fa-solid fa-circle-xmark me-2"></i> Đơn Hàng Đã Bị Hủy</h6>
            <p class="mb-1"><strong>Lý do hủy:</strong> {{ $order->cancel_reason ?? 'Không có lý do chi tiết.' }}</p>
            <small class="text-muted">Toàn bộ sản phẩm trong đơn đã được tự động hoàn lại số lượng vào kho.</small>
        </div>
    @else
        <!-- Step Indicator -->
        <div class="position-relative mx-4 my-5 py-2">
            <div class="progress" style="height: 4px;">
                @php
                    $percent = match($currentStep) {
                        1 => 0,
                        2 => 25,
                        3 => 50,
                        4 => 75,
                        5 => 100,
                        default => 0,
                    };
                @endphp
                <div class="progress-bar bg-success" role="progressbar" style="width: {{ $percent }}%;"></div>
            </div>

            <!-- Steps points -->
            <div class="position-absolute top-50 translate-middle text-center" style="left: 0%; width: 90px;">
                <div class="rounded-circle {{ $currentStep >= 1 ? 'bg-success text-white' : 'bg-light text-muted border' }} d-inline-flex align-items-center justify-content-center shadow-sm" style="width: 36px; height: 36px;">
                    <i class="fa-solid fa-file-invoice" style="font-size: 0.9rem;"></i>
                </div>
                <div class="small fw-semibold mt-2 {{ $currentStep >= 1 ? 'text-success' : 'text-muted' }}" style="font-size: 0.8rem;">Đặt hàng</div>
            </div>

            <div class="position-absolute top-50 translate-middle text-center" style="left: 25%; width: 90px;">
                <div class="rounded-circle {{ $currentStep >= 2 ? 'bg-success text-white' : 'bg-light text-muted border' }} d-inline-flex align-items-center justify-content-center shadow-sm" style="width: 36px; height: 36px;">
                    <i class="fa-solid fa-check" style="font-size: 0.9rem;"></i>
                </div>
                <div class="small fw-semibold mt-2 {{ $currentStep >= 2 ? 'text-success' : 'text-muted' }}" style="font-size: 0.8rem;">Xác nhận</div>
            </div>

            <div class="position-absolute top-50 translate-middle text-center" style="left: 50%; width: 90px;">
                <div class="rounded-circle {{ $currentStep >= 3 ? 'bg-success text-white' : 'bg-light text-muted border' }} d-inline-flex align-items-center justify-content-center shadow-sm" style="width: 36px; height: 36px;">
                    <i class="fa-solid fa-box" style="font-size: 0.9rem;"></i>
                </div>
                <div class="small fw-semibold mt-2 {{ $currentStep >= 3 ? 'text-success' : 'text-muted' }}" style="font-size: 0.8rem;">Chuẩn bị</div>
            </div>

            <div class="position-absolute top-50 translate-middle text-center" style="left: 75%; width: 90px;">
                <div class="rounded-circle {{ $currentStep >= 4 ? 'bg-success text-white' : 'bg-light text-muted border' }} d-inline-flex align-items-center justify-content-center shadow-sm" style="width: 36px; height: 36px;">
                    <i class="fa-solid fa-truck" style="font-size: 0.9rem;"></i>
                </div>
                <div class="small fw-semibold mt-2 {{ $currentStep >= 4 ? 'text-success' : 'text-muted' }}" style="font-size: 0.8rem;">Đang giao</div>
            </div>

            <div class="position-absolute top-50 translate-middle text-center" style="left: 100%; width: 90px;">
                <div class="rounded-circle {{ $currentStep >= 5 ? 'bg-success text-white' : 'bg-light text-muted border' }} d-inline-flex align-items-center justify-content-center shadow-sm" style="width: 36px; height: 36px;">
                    <i class="fa-solid fa-flag-checkered" style="font-size: 0.9rem;"></i>
                </div>
                <div class="small fw-semibold mt-2 {{ $currentStep >= 5 ? 'text-success' : 'text-muted' }}" style="font-size: 0.8rem;">Hoàn thành</div>
            </div>
        </div>
    @endif
</div>

<!-- Ordered Items Table -->
<div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
    <div class="card-header bg-white py-3 px-4">
        <h5 class="fw-bold mb-0">Danh Sách Sản Phẩm Đã Mua</h5>
    </div>
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th class="py-3 px-4">Sản phẩm</th>
                    <th class="py-3 text-center">Đơn giá</th>
                    <th class="py-3 text-center">Số lượng</th>
                    <th class="py-3 text-end px-4">Thành tiền</th>
                    @if($order->status === 'completed')
                        <th class="py-3 text-center">Đánh giá</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                    <tr>
                        <td class="py-3 px-4">
                            <div class="d-flex align-items-center">
                                <img src="{{ asset($item->product_image ?? 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=100') }}" 
                                     alt="{{ $item->product_name }}" 
                                     class="rounded-3 me-3 border" 
                                     style="width: 60px; height: 60px; object-fit: cover;">
                                <div>
                                    <h6 class="fw-bold mb-1">
                                        @if($item->product)
                                            <a href="{{ route('products.show', $item->product->slug) }}" class="text-dark text-decoration-none">
                                                {{ $item->product_name }}
                                            </a>
                                        @else
                                            {{ $item->product_name }}
                                        @endif
                                    </h6>
                                    @if($item->size || $item->color)
                                        <small class="text-muted">
                                            @if($item->size) Size: {{ $item->size }} @endif
                                            @if($item->color) - Màu: {{ $item->color }} @endif
                                        </small>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="text-center">{{ number_format($item->price, 0, ',', '.') }} ₫</td>
                        <td class="text-center font-monospace">{{ $item->quantity }}</td>
                        <td class="text-end px-4 fw-bold text-primary">{{ number_format($item->subtotal, 0, ',', '.') }} ₫</td>
                        @if($order->status === 'completed')
                            <td class="text-center">
                                @if($item->product)
                                    <a href="{{ route('products.show', $item->product->slug) }}#reviews-section" class="btn btn-sm btn-outline-warning rounded-pill">
                                        <i class="fa-solid fa-star me-1"></i> Đánh giá
                                    </a>
                                @endif
                            </td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- GHN Tracking Info (If pushed to GHN) -->
@if($order->ghn_order_code)
<div class="card border-0 shadow-sm rounded-4 mb-4 border-start border-4 border-primary overflow-hidden">
    <div class="card-header bg-primary bg-opacity-10 py-3 px-4 d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center gap-2">
            <span class="badge bg-primary px-3 py-2"><i class="fa-solid fa-truck-fast me-1"></i> Giao Hàng Nhanh (GHN)</span>
            <span class="fw-bold text-dark">Mã vận đơn: <span class="font-monospace text-primary fs-6">{{ $order->ghn_order_code }}</span></span>
        </div>
        <span class="badge {{ $order->ghn_status_badge }} px-3 py-2 fs-6">
            {{ $order->ghn_status_label }}
        </span>
    </div>
    <div class="card-body p-4">
        <div class="row g-3 mb-3">
            <div class="col-md-6">
                <div class="text-muted small">Dự kiến giao hàng:</div>
                <div class="fw-bold fs-6 text-success">
                    <i class="fa-regular fa-calendar-check me-1"></i>
                    {{ $order->ghn_expected_delivery ? $order->ghn_expected_delivery->format('d/m/Y H:i') : 'Đang cập nhật' }}
                </div>
            </div>
            <div class="col-md-6">
                <div class="text-muted small">Cước phí GHN thực tế:</div>
                <div class="fw-bold fs-6 text-dark">
                    {{ $order->ghn_fee ? number_format($order->ghn_fee, 0, ',', '.') . ' ₫' : 'Đang cập nhật' }}
                </div>
            </div>
        </div>

        @if(!empty($order->ghn_tracking_log) && is_array($order->ghn_tracking_log))
            <h6 class="fw-bold mb-3 small text-uppercase text-muted"><i class="fa-solid fa-route me-1"></i> Nhật ký hành trình vận chuyển</h6>
            <div class="timeline-simple ps-2 border-start border-2 border-primary-subtle ms-2">
                @foreach($order->ghn_tracking_log as $log)
                    <div class="mb-3 position-relative ps-3">
                        <div class="position-absolute top-0 start-0 translate-middle rounded-circle bg-primary" style="width: 10px; height: 10px; margin-top: 6px;"></div>
                        <div class="fw-semibold small text-dark">{{ $log['status'] ?? 'Cập nhật trạng thái' }}</div>
                        <div class="text-muted small" style="font-size: 0.8rem;">
                            {{ isset($log['time']) ? \Carbon\Carbon::parse($log['time'])->format('d/m/Y H:i:s') : '' }}
                            @if(isset($log['location'])) - {{ $log['location'] }} @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endif

<!-- Shipping & Financial Summary -->
<div class="row g-4">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm rounded-4 p-4 h-100">
            <h6 class="fw-bold mb-3 border-bottom pb-2">Thông Tin Giao Nhận & Thanh Toán</h6>
            <div class="d-flex flex-column gap-2 small">
                <div><span class="text-muted">Người nhận:</span> <strong>{{ $order->customer_name }}</strong></div>
                <div><span class="text-muted">Số điện thoại:</span> <strong>{{ $order->customer_phone }}</strong></div>
                <div><span class="text-muted">Email:</span> <strong>{{ $order->customer_email }}</strong></div>
                <div><span class="text-muted">Địa chỉ nhận hàng:</span> <strong>{{ $order->shipping_address }}</strong></div>
                @if($order->note)
                    <div><span class="text-muted">Ghi chú:</span> <em>{{ $order->note }}</em></div>
                @endif
                <div class="mt-2 pt-2 border-top">
                    <span class="text-muted">Phương thức thanh toán:</span> 
                    <strong>{{ $order->payment_method_label }}</strong>
                    @if($order->payment_method === 'momo')
                        <span class="badge text-white px-2 py-0 ms-1" style="background-color: #a50064;">MoMo</span>
                    @endif
                </div>
                <div>
                    <span class="text-muted">Tình trạng thanh toán:</span> 
                    <span class="badge {{ $order->payment_status === 'paid' ? 'bg-success' : 'bg-secondary' }}">
                        {{ $order->payment_status === 'paid' ? 'Đã thanh toán' : 'Chưa thanh toán' }}
                    </span>
                </div>

                @if($order->payment_method === 'momo' && $order->payment_status !== 'paid' && $order->status !== 'cancelled')
                    <div class="p-3 rounded-3 mt-2 border border-danger-subtle bg-danger-subtle bg-opacity-25">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="fw-bold text-dark small"><i class="fa-solid fa-circle-exclamation text-danger me-1"></i> Chưa hoàn tất thanh toán</span>
                            <a href="{{ route('orders.momo.pay', $order->id) }}" class="btn btn-sm text-white fw-bold px-3 rounded-pill" style="background-color: #a50064;">
                                <i class="fa-solid fa-wallet me-1"></i> Trả ngay
                            </a>
                        </div>
                        <div class="small text-muted" style="font-size: 0.8rem;">
                            Đơn hàng của bạn sẽ được kích hoạt tạo vận đơn GHN ngay sau khi MoMo xác nhận giao dịch thành công.
                        </div>
                    </div>
                @endif

                @if($order->paymentTransactions->count() > 0)
                    <div class="mt-3 pt-2 border-top">
                        <div class="fw-bold text-muted small mb-2"><i class="fa-solid fa-receipt me-1"></i> Nhật ký giao dịch thanh toán:</div>
                        <div class="d-flex flex-column gap-2">
                            @foreach($order->paymentTransactions as $pt)
                                <div class="p-2 rounded bg-light border d-flex justify-content-between align-items-center" style="font-size: 0.8rem;">
                                    <div>
                                        <span class="fw-bold text-uppercase">{{ $pt->gateway }}</span>
                                        @if($pt->transaction_id)
                                            <span class="text-muted">| Mã GD: <code>{{ $pt->transaction_id }}</code></span>
                                        @endif
                                        <div class="text-muted">{{ $pt->created_at->format('d/m/Y H:i:s') }}</div>
                                    </div>
                                    <div class="text-end">
                                        <div class="fw-bold">{{ number_format($pt->amount, 0, ',', '.') }} ₫</div>
                                        <span class="badge {{ $pt->status === 'paid' ? 'bg-success' : ($pt->status === 'failed' ? 'bg-danger' : 'bg-warning text-dark') }}">
                                            {{ $pt->status === 'paid' ? 'Thành công' : ($pt->status === 'failed' ? 'Thất bại' : 'Chờ xử lý') }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card border-0 shadow-sm rounded-4 p-4 h-100">
            <h6 class="fw-bold mb-3 border-bottom pb-2">Chi Phí Đơn Hàng</h6>
            <div class="d-flex justify-content-between mb-2 small">
                <span class="text-muted">Tạm tính:</span>
                <span class="fw-semibold">{{ number_format($order->subtotal, 0, ',', '.') }} ₫</span>
            </div>

            @if($order->discount_amount > 0)
                <div class="d-flex justify-content-between mb-2 small text-success">
                    <span>Mã giảm giá ({{ $order->coupon_code }}):</span>
                    <span class="fw-semibold">-{{ number_format($order->discount_amount, 0, ',', '.') }} ₫</span>
                </div>
            @endif

            <div class="d-flex justify-content-between mb-3 small">
                <span class="text-muted">Phí vận chuyển:</span>
                <span class="fw-semibold">
                    @if($order->shipping_fee == 0)
                        <span class="text-success">Miễn phí</span>
                    @else
                        {{ number_format($order->shipping_fee, 0, ',', '.') }} ₫
                    @endif
                </span>
            </div>

            <hr>

            <div class="d-flex justify-content-between align-items-baseline">
                <span class="fw-bold fs-6">Tổng cộng:</span>
                <span class="fw-extrabold fs-4 text-primary">{{ number_format($order->total_amount, 0, ',', '.') }} ₫</span>
            </div>
        </div>
    </div>
</div>

<!-- Cancel Order Modal -->
@if($order->canBeCancelled())
    <div class="modal fade" id="cancelOrderModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content rounded-4 border-0">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold text-danger"><i class="fa-solid fa-triangle-exclamation me-2"></i> Xác Nhận Hủy Đơn</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('account.orders.cancel', $order->id) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <p class="small text-muted mb-3">Bạn chỉ có thể hủy đơn khi đơn hàng đang ở trạng thái <strong>Chờ xử lý</strong>. Sau khi hủy, sản phẩm sẽ được tự động hoàn lại kho.</p>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Lý do hủy đơn <span class="text-danger">*</span></label>
                            <textarea name="reason" class="form-control" rows="3" placeholder="Ví dụ: Muốn đổi sang sản phẩm khác, đổi địa chỉ nhận hàng..." required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0">
                        <button type="button" class="btn btn-light rounded-pill px-3" data-bs-dismiss="modal">Đóng</button>
                        <button type="submit" class="btn btn-danger rounded-pill px-4 fw-bold">Xác Nhận Hủy Đơn</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif
@endsection
