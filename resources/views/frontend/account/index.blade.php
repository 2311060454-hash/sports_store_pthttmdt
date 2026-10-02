@extends('frontend.account.layout')

@section('title', 'Tổng Quan Tài Khoản - Sport Store')

@section('account_breadcrumb')
    <li class="breadcrumb-item active text-primary fw-semibold" aria-current="page">Tổng quan</li>
@endsection

@section('account_content')
<!-- Loyalty & Membership Tier Card (Chương trình khách hàng thân thiết) -->
<div class="card border-0 shadow-sm rounded-4 p-4 mb-4 text-white" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);">
    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
        <div>
            <span class="badge bg-warning text-dark fw-bold text-uppercase px-3 py-1 mb-2">
                <i class="fa-solid fa-crown me-1"></i> Hạng Hội Viên: {{ $tierName }}
            </span>
            <h4 class="fw-bold mb-1 text-white">{{ $user->name }}</h4>
            <p class="small text-light opacity-75 mb-0">
                <i class="fa-solid fa-coins text-warning me-1"></i> Điểm tích lũy: <strong class="text-warning fs-6">{{ number_format($loyaltyPoints) }}</strong> điểm 
                (Tổng chi tiêu: {{ number_format($totalSpent, 0, ',', '.') }} ₫)
            </p>
        </div>
        <div class="text-end">
            <span class="small text-light opacity-75 d-block mb-1">Mục tiêu nâng hạng: {{ number_format($tierNext) }} điểm</span>
            <div class="progress bg-white bg-opacity-25" style="height: 8px; width: 140px;">
                <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $tierProgress }}%;" aria-valuenow="{{ $tierProgress }}" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
            <small class="text-warning" style="font-size: 0.72rem;">Đạt {{ $tierProgress }}% tiến độ</small>
        </div>
    </div>
</div>

<!-- KPI Cards -->
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-4 p-4 text-center bg-white">
            <div class="text-primary mb-2 fs-3"><i class="fa-solid fa-box"></i></div>
            <h3 class="fw-bold mb-1">{{ $totalOrdersCount }}</h3>
            <span class="text-muted small">Tổng đơn đã đặt</span>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-4 p-4 text-center bg-white">
            <div class="text-danger mb-2 fs-3"><i class="fa-solid fa-heart"></i></div>
            <h3 class="fw-bold mb-1">{{ $wishlistCount }}</h3>
            <span class="text-muted small">Sản phẩm yêu thích</span>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-4 p-4 text-center bg-white">
            <div class="text-success mb-2 fs-3"><i class="fa-solid fa-map-location-dot"></i></div>
            <h3 class="fw-bold mb-1">{{ $user->addresses()->count() }}</h3>
            <span class="text-muted small">Địa chỉ nhận hàng</span>
        </div>
    </div>
</div>

<!-- Recent Orders Card -->
<div class="card border-0 shadow-sm rounded-4 p-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-bold mb-0">Đơn Hàng Gần Đây</h5>
        <a href="{{ route('account.orders') }}" class="small text-primary text-decoration-none fw-bold">Xem tất cả <i class="fa-solid fa-arrow-right ms-1"></i></a>
    </div>

    @if($recentOrders->count() > 0)
        <div class="table-responsive">
            <table class="table align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Mã đơn</th>
                        <th>Ngày đặt</th>
                        <th>Tổng tiền</th>
                        <th>Trạng thái</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentOrders as $order)
                        <tr>
                            <td><strong class="font-monospace text-primary">#{{ $order->order_code }}</strong></td>
                            <td class="small">{{ $order->created_at->format('d/m/Y') }}</td>
                            <td class="fw-bold">{{ number_format($order->total_amount, 0, ',', '.') }} ₫</td>
                            <td><span class="badge {{ $order->status_badge_class }}">{{ $order->status_label }}</span></td>
                            <td class="text-end">
                                <a href="{{ route('account.orders.show', $order->id) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">Chi tiết</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="text-center py-4 text-muted">
            <i class="fa-solid fa-box-open fs-2 mb-2 d-block"></i>
            Bạn chưa có đơn hàng nào tại Sport Store.
        </div>
    @endif
</div>
@endsection
