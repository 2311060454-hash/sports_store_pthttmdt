<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hóa đơn #{{ $order->order_code }} - Sport Store</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
        body {
            background-color: #f8fafc;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            color: #1e293b;
        }
        .invoice-card {
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.06);
            padding: 40px;
            max-width: 850px;
            margin: 30px auto;
        }
        .invoice-title {
            color: #ff5722;
            font-weight: 800;
            letter-spacing: -0.5px;
        }
        @media print {
            body {
                background-color: #ffffff;
            }
            .invoice-card {
                box-shadow: none;
                padding: 0;
                margin: 0;
                max-width: 100%;
            }
            .no-print {
                display: none !important;
            }
        }
    </style>
</head>
<body>

<div class="container py-4">
    <!-- Action buttons -->
    <div class="d-flex justify-content-between align-items-center mb-4 max-w-850 mx-auto no-print" style="max-width: 850px;">
        <a href="{{ url()->previous() ?: route('account.orders') }}" class="btn btn-outline-secondary rounded-pill px-4">
            <i class="fa-solid fa-arrow-left me-2"></i> Quay lại
        </a>
        <button onclick="window.print()" class="btn btn-primary rounded-pill px-4" style="background-color: #ff5722; border-color: #ff5722;">
            <i class="fa-solid fa-print me-2"></i> In Hóa Đơn
        </button>
    </div>

    <!-- Invoice Card -->
    <div class="invoice-card">
        <!-- Header -->
        <div class="row align-items-center border-bottom pb-4 mb-4">
            <div class="col-sm-6">
                <div class="d-flex align-items-center mb-2">
                    <i class="fa-solid fa-bolt text-warning fs-2 me-2"></i>
                    <span class="fs-3 fw-bold invoice-title">SPORT<span class="text-dark">STORE</span></span>
                </div>
                <div class="small text-muted">Hệ thống thương mại điện tử dụng cụ thể thao cao cấp</div>
                <div class="small text-muted mt-1"><i class="fa-solid fa-location-dot me-1"></i> 123 Cầu Giấy, Q. Cầu Giấy, Hà Nội</div>
                <div class="small text-muted"><i class="fa-solid fa-phone me-1"></i> Hotline: 1900 8888 | support@sportstore.com</div>
            </div>
            <div class="col-sm-6 text-sm-end mt-3 mt-sm-0">
                <h4 class="fw-bold text-uppercase mb-1">Hóa Đơn Bán Hàng</h4>
                <div class="text-muted small">Mã đơn: <strong class="text-primary font-monospace">#{{ $order->order_code }}</strong></div>
                <div class="text-muted small">Ngày đặt: {{ $order->created_at->format('d/m/Y H:i') }}</div>
                <div class="mt-2">
                    <span class="badge {{ $order->payment_status === 'paid' ? 'bg-success' : 'bg-warning text-dark' }} px-3 py-1">
                        {{ $order->payment_status === 'paid' ? 'Đã Thanh Toán' : 'Chưa Thanh Toán' }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Customer & Shipping info -->
        <div class="row mb-4">
            <div class="col-sm-6">
                <h6 class="fw-bold text-muted text-uppercase mb-2" style="font-size: 0.8rem;">Thông Tin Khách Hàng:</h6>
                <div class="fw-bold">{{ $order->customer_name }}</div>
                <div class="small text-muted"><i class="fa-solid fa-phone me-1"></i> {{ $order->customer_phone }}</div>
                <div class="small text-muted"><i class="fa-solid fa-envelope me-1"></i> {{ $order->customer_email }}</div>
            </div>
            <div class="col-sm-6 mt-3 mt-sm-0">
                <h6 class="fw-bold text-muted text-uppercase mb-2" style="font-size: 0.8rem;">Địa Chỉ Giao Hàng & Phương Thức:</h6>
                <div class="small text-dark fw-semibold">{{ $order->shipping_address }}</div>
                <div class="small text-muted mt-1">Phương thức thanh toán: <strong>{{ $order->payment_method_label }}</strong></div>
                @if($order->note)
                    <div class="small text-muted mt-1 fst-italic">Ghi chú: {{ $order->note }}</div>
                @endif
            </div>
        </div>

        <!-- Items Table -->
        <div class="table-responsive mb-4">
            <table class="table table-bordered table-striped align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="text-center" style="width: 50px;">STT</th>
                        <th>Sản phẩm / Dụng cụ thể thao</th>
                        <th class="text-center" style="width: 130px;">Phân loại</th>
                        <th class="text-end" style="width: 130px;">Đơn giá</th>
                        <th class="text-center" style="width: 90px;">SL</th>
                        <th class="text-end" style="width: 140px;">Thành tiền</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $index => $item)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td>
                                <div class="fw-bold">{{ $item->product_name }}</div>
                            </td>
                            <td class="text-center small text-muted">
                                @if($item->size || $item->color)
                                    {{ $item->size ? 'Size: ' . $item->size : '' }} {{ $item->color ? ' | ' . $item->color : '' }}
                                @else
                                    Mặc định
                                @endif
                            </td>
                            <td class="text-end">{{ number_format($item->price, 0, ',', '.') }} ₫</td>
                            <td class="text-center fw-bold">{{ $item->quantity }}</td>
                            <td class="text-end fw-bold">{{ number_format($item->subtotal, 0, ',', '.') }} ₫</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="5" class="text-end fw-semibold">Tạm tính:</td>
                        <td class="text-end fw-semibold">{{ number_format($order->subtotal, 0, ',', '.') }} ₫</td>
                    </tr>
                    @if($order->discount_amount > 0)
                        <tr>
                            <td colspan="5" class="text-end text-danger fw-semibold">Giảm giá mã ưu đãi:</td>
                            <td class="text-end text-danger fw-semibold">-{{ number_format($order->discount_amount, 0, ',', '.') }} ₫</td>
                        </tr>
                    @endif
                    <tr>
                        <td colspan="5" class="text-end fw-semibold">Phí vận chuyển:</td>
                        <td class="text-end fw-semibold">
                            @if($order->shipping_fee > 0)
                                {{ number_format($order->shipping_fee, 0, ',', '.') }} ₫
                            @else
                                <span class="text-success">Miễn phí</span>
                            @endif
                        </td>
                    </tr>
                    <tr class="table-active">
                        <td colspan="5" class="text-end fw-bold fs-6">TỔNG CỘNG THANH TOÁN:</td>
                        <td class="text-end fw-extrabold fs-5" style="color: #ff5722;">{{ number_format($order->total_amount, 0, ',', '.') }} ₫</td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- Signatures -->
        <div class="row text-center mt-5 pt-3">
            <div class="col-4">
                <div class="fw-bold">Khách Hàng</div>
                <small class="text-muted">(Ký & ghi rõ họ tên)</small>
            </div>
            <div class="col-4">
                <div class="fw-bold">Người Giao Hàng</div>
                <small class="text-muted">(Ký & ghi rõ họ tên)</small>
            </div>
            <div class="col-4">
                <div class="fw-bold">Đại Diện Cửa Hàng</div>
                <small class="text-muted">(Ký & đóng dấu)</small>
                <div class="mt-4 fw-semibold text-danger fst-italic">Sport Store Official</div>
            </div>
        </div>

        <div class="text-center text-muted small mt-5 pt-4 border-top">
            Cảm ơn quý khách đã tin dùng sản phẩm của Sport Store. Mọi thắc mắc và khiếu nại bảo hành xin liên hệ Hotline: <strong>1900 8888</strong>.
        </div>
    </div>
</div>

</body>
</html>
