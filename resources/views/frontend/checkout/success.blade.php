@extends('layouts.app')

@section('title', 'Đặt Hàng Thành Công #' . $order->order_code . ' - Sport Store')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 text-center mb-4">
                <div class="mb-4">
                    <div class="rounded-circle bg-success text-white d-inline-flex align-items-center justify-content-center shadow" style="width: 80px; height: 80px;">
                        <i class="fa-solid fa-check fs-1"></i>
                    </div>
                </div>

                <h3 class="fw-extrabold text-success mb-2">Đặt Hàng Thành Công!</h3>
                <p class="text-muted mb-4">
                    Cảm ơn bạn đã tin tưởng mua sắm tại <strong>Sport Store</strong>. Mã đơn hàng của bạn là: 
                    <span class="badge bg-primary fs-6 px-3 py-2 ms-1 font-monospace">#{{ $order->order_code }}</span>
                </p>

                <!-- Payment Instructions -->
                @if($paymentInfo && $order->payment_method === 'bank_transfer')
                    <div class="alert alert-info text-start rounded-3 p-4 mb-4">
                        <h6 class="fw-bold mb-2"><i class="fa-solid fa-building-columns me-2"></i> Hướng Dẫn Chuyển Khoản Ngân Hàng</h6>
                        <p class="small mb-2">Vui lòng quét mã QR hoặc chuyển khoản chính xác số tiền vào tài khoản bên dưới:</p>
                        <ul class="list-unstyled small mb-0 d-flex flex-column gap-1">
                            <li><strong>Ngân hàng:</strong> {{ $paymentInfo['bank_details']['bank'] }}</li>
                            <li><strong>Số tài khoản:</strong> <span class="badge bg-dark font-monospace fs-6">{{ $paymentInfo['bank_details']['account_number'] }}</span></li>
                            <li><strong>Chủ tài khoản:</strong> {{ $paymentInfo['bank_details']['account_holder'] }}</li>
                            <li><strong>Số tiền:</strong> <strong class="text-danger">{{ number_format($paymentInfo['bank_details']['amount'], 0, ',', '.') }} ₫</strong></li>
                            <li><strong>Nội dung chuyển khoản:</strong> <strong class="text-primary">{{ $paymentInfo['bank_details']['syntax'] }}</strong></li>
                        </ul>
                    </div>
                @elseif($order->payment_method === 'cod')
                    <div class="alert alert-warning text-start rounded-3 p-3 mb-4">
                        <i class="fa-solid fa-truck-ramp-box me-2"></i>
                        Đơn hàng của bạn sẽ được nhân viên liên hệ xác nhận và tiến hành đóng gói giao đến địa chỉ trong 2-3 ngày làm việc. Quý khách vui lòng chuẩn bị số tiền <strong>{{ number_format($order->total_amount, 0, ',', '.') }} ₫</strong> khi nhận hàng.
                    </div>
                @elseif($order->payment_method === 'vnpay')
                    <div class="card border-0 bg-danger-subtle text-start rounded-4 p-4 mb-4">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h5 class="fw-bold mb-0 text-danger"><i class="fa-solid fa-qrcode me-2"></i> Cổng Thanh Toán VNPay QR</h5>
                            <span class="badge {{ $order->payment_status === 'paid' ? 'bg-success' : 'bg-warning text-dark' }} px-3 py-2">
                                {{ $order->payment_status === 'paid' ? 'Đã Thanh Toán' : 'Đang Chờ Thanh Toán' }}
                            </span>
                        </div>
                        @if($order->payment_status !== 'paid')
                            <p class="small text-secondary mb-3">
                                Bạn đã chọn thanh toán qua <strong>VNPay</strong>. Trong môi trường kiểm thử/demo đồ án, bạn có thể nhấn nút bên dưới để mô phỏng luồng thanh toán thành công và cập nhật trạng thái đơn hàng ngay:
                            </p>
                            <a href="{{ route('payment.vnpay.callback', ['vnp_TxnRef' => $order->order_code, 'vnp_ResponseCode' => '00']) }}" class="btn btn-danger rounded-pill px-4 fw-bold">
                                <i class="fa-solid fa-circle-check me-2"></i> Xác Nhận Thanh Toán VNPay (Mô Phỏng Demo)
                            </a>
                        @else
                            <div class="alert alert-success d-flex align-items-center mb-0">
                                <i class="fa-solid fa-circle-check fs-4 me-3"></i>
                                <div>Giao dịch thanh toán trực tuyến qua <strong>VNPay</strong> đã được hệ thống xác nhận thành công!</div>
                            </div>
                        @endif
                    </div>
                @endif

                <!-- Order Information Card -->
                <div class="card bg-light border-0 rounded-3 text-start p-4 mb-4">
                    <h6 class="fw-bold mb-3 border-bottom pb-2">Chi Tiết Đơn Hàng</h6>
                    <div class="row g-3 small mb-3">
                        <div class="col-sm-6">
                            <span class="text-muted">Người nhận:</span> <strong>{{ $order->customer_name }}</strong>
                        </div>
                        <div class="col-sm-6">
                            <span class="text-muted">Số điện thoại:</span> <strong>{{ $order->customer_phone }}</strong>
                        </div>
                        <div class="col-sm-12">
                            <span class="text-muted">Địa chỉ nhận hàng:</span> <strong>{{ $order->shipping_address }}</strong>
                        </div>
                        <div class="col-sm-6">
                            <span class="text-muted">Phương thức thanh toán:</span> <strong>{{ $order->payment_method_label }}</strong>
                        </div>
                        <div class="col-sm-6">
                            <span class="text-muted">Tổng thanh toán:</span> <strong class="text-primary fs-6">{{ number_format($order->total_amount, 0, ',', '.') }} ₫</strong>
                        </div>
                    </div>

                    <!-- Items Table -->
                    <div class="table-responsive">
                        <table class="table table-sm align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Sản phẩm</th>
                                    <th class="text-center">Số lượng</th>
                                    <th class="text-end">Thành tiền</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                    <tr>
                                        <td>
                                            <div class="fw-semibold">{{ $item->product_name }}</div>
                                            @if($item->size || $item->color)
                                                <small class="text-muted">
                                                    @if($item->size) Size: {{ $item->size }} @endif
                                                    @if($item->color) Màu: {{ $item->color }} @endif
                                                </small>
                                            @endif
                                        </td>
                                        <td class="text-center">{{ $item->quantity }}</td>
                                        <td class="text-end fw-bold">{{ number_format($item->subtotal, 0, ',', '.') }} ₫</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="d-flex flex-wrap justify-content-center gap-3">
                    <a href="{{ route('account.orders.invoice', $order->id) }}" target="_blank" class="btn btn-outline-dark rounded-pill px-4 fw-bold">
                        <i class="fa-solid fa-print me-2"></i> In Hóa Đơn
                    </a>
                    <a href="{{ route('account.orders.show', $order->id) }}" class="btn btn-primary rounded-pill px-4 fw-bold">
                        <i class="fa-solid fa-clock-rotate-left me-2"></i> Theo Dõi Đơn Hàng
                    </a>
                    <a href="{{ route('home') }}" class="btn btn-outline-secondary rounded-pill px-4 fw-bold">
                        Tiếp Tục Mua Sắm
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
