<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xác nhận đơn hàng #{{ $order->order_code }}</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background-color: #f8fafc;
            color: #334155;
            margin: 0;
            padding: 0;
            line-height: 1.6;
        }
        .container {
            max-width: 650px;
            margin: 30px auto;
            background-color: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            border: 1px solid #e2e8f0;
        }
        .header {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            padding: 30px;
            text-align: center;
            color: #ffffff;
        }
        .header h1 {
            margin: 0;
            font-size: 26px;
            font-weight: 800;
            letter-spacing: -0.5px;
        }
        .header span {
            color: #ff5722;
        }
        .content {
            padding: 30px;
        }
        .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 50px;
            font-size: 13px;
            font-weight: bold;
        }
        .badge-success {
            background-color: #dcfce7;
            color: #15803d;
        }
        .badge-warning {
            background-color: #fef3c7;
            color: #b45309;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        .table th {
            background-color: #f1f5f9;
            color: #475569;
            padding: 12px;
            text-align: left;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .table td {
            padding: 12px;
            border-bottom: 1px solid #f1f5f9;
            font-size: 14px;
        }
        .btn {
            display: inline-block;
            background-color: #ff5722;
            color: #ffffff !important;
            text-decoration: none;
            padding: 12px 28px;
            border-radius: 50px;
            font-weight: bold;
            font-size: 15px;
            margin-top: 15px;
        }
        .footer {
            background-color: #f8fafc;
            padding: 20px 30px;
            text-align: center;
            font-size: 13px;
            color: #94a3b8;
            border-top: 1px solid #e2e8f0;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>SPORT<span>STORE</span></h1>
            <p style="margin: 5px 0 0 0; color: #94a3b8; font-size: 14px;">Cảm ơn bạn đã tin tưởng mua sắm!</p>
        </div>

        <!-- Content -->
        <div class="content">
            <h2 style="color: #0f172a; margin-top: 0; font-size: 20px;">Chào {{ $order->customer_name }},</h2>
            <p>
                Đơn hàng <strong>#{{ $order->order_code }}</strong> của bạn đã được tiếp nhận thành công vào lúc 
                {{ $order->created_at->format('d/m/Y H:i') }}. Chúng tôi đang tiến hành đóng gói và sẽ bàn giao cho đối tác vận chuyển <strong>Giao Hàng Nhanh (GHN)</strong> trong thời gian sớm nhất.
            </p>

            <!-- Order info cards -->
            <table style="width: 100%; margin-bottom: 20px;">
                <tr>
                    <td style="width: 50%; vertical-align: top; padding-right: 10px;">
                        <div style="background-color: #f8fafc; border-radius: 8px; padding: 15px; border: 1px solid #e2e8f0;">
                            <strong style="color: #0f172a; font-size: 14px; display: block; margin-bottom: 8px;">Địa Chỉ Giao Hàng</strong>
                            <div style="font-size: 13px; color: #475569;">
                                <strong>{{ $order->customer_name }}</strong><br>
                                SĐT: {{ $order->customer_phone }}<br>
                                {{ $order->shipping_address }}
                            </div>
                        </div>
                    </td>
                    <td style="width: 50%; vertical-align: top; padding-left: 10px;">
                        <div style="background-color: #f8fafc; border-radius: 8px; padding: 15px; border: 1px solid #e2e8f0;">
                            <strong style="color: #0f172a; font-size: 14px; display: block; margin-bottom: 8px;">Hình Thức Thanh Toán</strong>
                            <div style="font-size: 13px; color: #475569;">
                                {{ $order->payment_method === 'momo' ? 'Ví MoMo' : ($order->payment_method === 'bank_transfer' ? 'Chuyển khoản ngân hàng' : 'Tiền mặt khi nhận hàng (COD)') }}<br>
                                Trạng thái: 
                                @if($order->payment_status === 'paid')
                                    <span class="badge badge-success">Đã thanh toán</span>
                                @else
                                    <span class="badge badge-warning">Chờ thanh toán</span>
                                @endif
                                @if($order->ghn_order_code)
                                    <br>Mã vận đơn GHN: <strong>{{ $order->ghn_order_code }}</strong>
                                @endif
                            </div>
                        </div>
                    </td>
                </tr>
            </table>

            <!-- Items Table -->
            <strong style="color: #0f172a; font-size: 15px;">Chi Tiết Sản Phẩm Đặt Mua:</strong>
            <table class="table">
                <thead>
                    <tr>
                        <th>Sản phẩm</th>
                        <th style="text-align: center;">SL</th>
                        <th style="text-align: right;">Đơn giá</th>
                        <th style="text-align: right;">Thành tiền</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                        <tr>
                            <td>
                                <strong>{{ $item->product_name }}</strong>
                                @if($item->size || $item->color)
                                    <br><span style="font-size: 12px; color: #64748b;">
                                        @if($item->size) Size: {{ $item->size }} @endif
                                        @if($item->color) Màu: {{ $item->color }} @endif
                                    </span>
                                @endif
                            </td>
                            <td style="text-align: center;">{{ $item->quantity }}</td>
                            <td style="text-align: right;">{{ number_format($item->price, 0, ',', '.') }} ₫</td>
                            <td style="text-align: right; font-weight: bold;">{{ number_format($item->subtotal, 0, ',', '.') }} ₫</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" style="text-align: right; font-size: 13px;">Tạm tính:</td>
                        <td style="text-align: right;">{{ number_format($order->subtotal, 0, ',', '.') }} ₫</td>
                    </tr>
                    @if($order->discount_amount > 0)
                        <tr>
                            <td colspan="3" style="text-align: right; font-size: 13px; color: #16a34a;">Giảm giá (Coupon):</td>
                            <td style="text-align: right; color: #16a34a;">-{{ number_format($order->discount_amount, 0, ',', '.') }} ₫</td>
                        </tr>
                    @endif
                    <tr>
                        <td colspan="3" style="text-align: right; font-size: 13px;">Phí giao hàng GHN:</td>
                        <td style="text-align: right;">{{ number_format($order->shipping_fee, 0, ',', '.') }} ₫</td>
                    </tr>
                    <tr style="font-weight: bold; font-size: 16px; color: #0f172a; background-color: #f8fafc;">
                        <td colspan="3" style="text-align: right;">Tổng thanh toán:</td>
                        <td style="text-align: right; color: #ff5722;">{{ number_format($order->total_amount, 0, ',', '.') }} ₫</td>
                    </tr>
                </tfoot>
            </table>

            <!-- Action CTA -->
            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ route('orders.tracking', ['code' => $order->order_code, 'phone' => $order->customer_phone]) }}" class="btn">
                    Tra Cứu Trạng Thái Đơn Hàng
                </a>
            </div>

            <p style="font-size: 13px; color: #64748b; margin-top: 25px;">
                * Quý khách được quyền đồng kiểm hàng hóa khi nhận từ shipper Giao Hàng Nhanh (GHN). Nếu cần hỗ trợ thêm, vui lòng phản hồi email này hoặc gọi hotline <strong>1900 8888</strong>.
            </p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p style="margin: 0 0 5px 0;"><strong>Sport Store - Đồ Thể Thao Chính Hãng</strong></p>
            <p style="margin: 0;">123 Đường Thể Thao, Quận 1, TP. Hồ Chí Minh | Hotline: 1900 8888</p>
            <p style="margin: 5px 0 0 0; font-size: 11px;">Đây là email tự động từ hệ thống. Quý khách không cần trả lời thư này.</p>
        </div>
    </div>
</body>
</html>
