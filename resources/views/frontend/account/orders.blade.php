@extends('frontend.account.layout')

@section('title', 'Lịch Sử Đơn Hàng - Sport Store')

@section('account_breadcrumb')
    <li class="breadcrumb-item active text-primary fw-semibold" aria-current="page">Đơn hàng của tôi</li>
@endsection

@section('account_content')
<div class="card border-0 shadow-sm rounded-4 p-4">
    <h4 class="fw-bold mb-4">Lịch Sử Đơn Hàng Của Bạn</h4>

    @if($orders->count() > 0)
        <div class="table-responsive">
            <table class="table align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Mã đơn hàng</th>
                        <th>Ngày đặt</th>
                        <th>Sản phẩm</th>
                        <th>Tổng tiền</th>
                        <th>Thanh toán</th>
                        <th>Trạng thái</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                        <tr>
                            <td>
                                <strong class="font-monospace text-primary">#{{ $order->order_code }}</strong>
                            </td>
                            <td class="small">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                            <td class="small">
                                {{ $order->items->count() }} món
                            </td>
                            <td>
                                <strong class="text-dark">{{ number_format($order->total_amount, 0, ',', '.') }} ₫</strong>
                            </td>
                            <td>
                                <span class="badge {{ $order->payment_status === 'paid' ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $order->payment_status === 'paid' ? 'Đã thanh toán' : 'Chưa thanh toán' }}
                                </span>
                            </td>
                            <td>
                                <span class="badge {{ $order->status_badge_class }}">
                                    {{ $order->status_label }}
                                </span>
                            </td>
                            <td class="text-end">
                                <a href="{{ route('account.orders.show', $order->id) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                    Chi tiết <i class="fa-solid fa-chevron-right ms-1"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $orders->links('pagination::bootstrap-5') }}
        </div>
    @else
        <div class="text-center py-5 text-muted">
            <i class="fa-solid fa-box-open fs-1 mb-3 text-secondary"></i>
            <h5>Bạn chưa có đơn hàng nào!</h5>
            <p class="small">Khi bạn mua hàng, thông tin theo dõi đơn hàng sẽ hiển thị tại đây.</p>
            <a href="{{ route('products.index') }}" class="btn btn-primary rounded-pill px-4 mt-2">
                Khám phá sản phẩm
            </a>
        </div>
    @endif
</div>
@endsection
