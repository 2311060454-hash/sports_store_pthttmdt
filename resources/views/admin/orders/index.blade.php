@extends('layouts.admin')

@section('title', 'Quản Lý Đơn Hàng')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Quản Lý Đơn Hàng</h4>
        <p class="text-muted small mb-0">Theo dõi, xác nhận và xử lý tiến trình giao hàng</p>
    </div>
</div>

<!-- Filters Card -->
<div class="card mb-4">
    <div class="card-body p-3">
        <form action="{{ route('admin.orders.index') }}" method="GET" class="row g-2 align-items-center">
            <div class="col-md-4">
                <input type="text" name="q" class="form-control form-control-sm" placeholder="Tìm theo mã đơn, họ tên, SĐT..." value="{{ request('q') }}">
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select form-select-sm">
                    <option value="">Tất cả trạng thái</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Chờ xử lý</option>
                    <option value="confirmed" {{ request('status') === 'confirmed' ? 'selected' : '' }}>Đã xác nhận</option>
                    <option value="preparing" {{ request('status') === 'preparing' ? 'selected' : '' }}>Đang chuẩn bị</option>
                    <option value="shipping" {{ request('status') === 'shipping' ? 'selected' : '' }}>Đang giao hàng</option>
                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Đã hoàn thành</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                </select>
            </div>
            <div class="col-md-3">
                <select name="payment_status" class="form-select form-select-sm">
                    <option value="">Tất cả thanh toán</option>
                    <option value="unpaid" {{ request('payment_status') === 'unpaid' ? 'selected' : '' }}>Chưa thanh toán</option>
                    <option value="paid" {{ request('payment_status') === 'paid' ? 'selected' : '' }}>Đã thanh toán</option>
                </select>
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-sm btn-primary w-100 rounded-pill">Lọc</button>
                <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill">Reset</a>
            </div>
        </form>
    </div>
</div>

<!-- Orders Table -->
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="px-3">Mã đơn</th>
                        <th>Khách hàng</th>
                        <th>Số điện thoại</th>
                        <th>Ngày đặt</th>
                        <th>Tổng tiền</th>
                        <th>Thanh toán</th>
                        <th>Trạng thái</th>
                        <th class="text-end px-3">Chi tiết</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr>
                            <td class="px-3">
                                <strong class="font-monospace text-primary">#{{ $order->order_code }}</strong>
                            </td>
                            <td>
                                <div class="fw-semibold">{{ $order->customer_name }}</div>
                                <small class="text-muted">{{ $order->customer_email }}</small>
                            </td>
                            <td class="font-monospace">{{ $order->customer_phone }}</td>
                            <td class="small">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <strong class="text-dark">{{ number_format($order->total_amount, 0, ',', '.') }} ₫</strong>
                            </td>
                            <td>
                                <span class="badge {{ $order->payment_status === 'paid' ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $order->payment_status === 'paid' ? 'Đã thu' : 'Chưa thu' }}
                                </span>
                                <small class="d-block text-muted" style="font-size: 0.7rem;">{{ strtoupper($order->payment_method) }}</small>
                            </td>
                            <td>
                                <span class="badge {{ $order->status_badge_class }}">
                                    {{ $order->status_label }}
                                </span>
                            </td>
                            <td class="text-end px-3">
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                    <i class="fa-solid fa-eye me-1"></i> Xem
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">Không tìm thấy đơn hàng nào.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($orders->hasPages())
        <div class="card-footer bg-white py-3 d-flex justify-content-center">
            {{ $orders->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>
@endsection
