@extends('layouts.admin')

@section('title', 'Chi Tiết Khách Hàng - ' . $customer->name)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Hồ Sơ Khách Hàng</h4>
        <p class="text-muted small mb-0">{{ $customer->name }} (ID: {{ $customer->id }})</p>
    </div>
    <a href="{{ route('admin.customers.index') }}" class="btn btn-outline-secondary rounded-pill px-3">
        <i class="fa-solid fa-arrow-left me-1"></i> Quay lại
    </a>
</div>

<div class="row g-4">
    <div class="col-lg-4">
        <div class="card p-4 text-center">
            <div class="mb-3">
                @if($customer->avatar)
                    <img src="{{ asset($customer->avatar) }}" class="rounded-circle border" style="width: 90px; height: 90px; object-fit: cover;">
                @else
                    <div class="rounded-circle bg-warning-subtle text-warning fw-bold d-flex align-items-center justify-content-center mx-auto" style="width: 90px; height: 90px; font-size: 2.5rem;">
                        {{ strtoupper(substr($customer->name, 0, 1)) }}
                    </div>
                @endif
            </div>

            <h5 class="fw-bold mb-1">{{ $customer->name }}</h5>
            <span class="text-muted small mb-3 d-block">{{ $customer->email }}</span>

            <div class="mb-3">
                <span class="badge {{ $customer->status === 'active' ? 'bg-success' : 'bg-danger' }} fs-6 px-3 py-1">
                    {{ $customer->status === 'active' ? 'Tài khoản hoạt động' : 'Tài khoản bị khóa' }}
                </span>
            </div>

            <form action="{{ route('admin.customers.toggle-status', $customer->id) }}" method="POST" class="mb-3">
                @csrf
                @method('PUT')
                <button type="submit" class="btn btn-outline-{{ $customer->status === 'active' ? 'danger' : 'success' }} rounded-pill px-4 btn-sm fw-bold">
                    <i class="fa-solid {{ $customer->status === 'active' ? 'fa-lock' : 'fa-lock-open' }} me-1"></i>
                    {{ $customer->status === 'active' ? 'Khóa tài khoản này' : 'Mở khóa tài khoản' }}
                </button>
            </form>

            <hr>

            <div class="text-start small d-flex flex-column gap-2">
                <div><span class="text-muted">Số điện thoại:</span> <strong>{{ $customer->phone ?? 'Chưa cập nhật' }}</strong></div>
                <div><span class="text-muted">Ngày tham gia:</span> <strong>{{ $customer->created_at->format('d/m/Y H:i') }}</strong></div>
                <div><span class="text-muted">Tổng đơn hàng:</span> <strong>{{ $customer->orders_count }} đơn</strong></div>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card">
            <div class="card-header bg-white py-3">
                <h5 class="fw-bold mb-0">Lịch Sử Mua Hàng</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="px-3">Mã đơn</th>
                                <th>Ngày đặt</th>
                                <th>Tổng tiền</th>
                                <th>Trạng thái</th>
                                <th class="text-end px-3">Chi tiết</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($orders as $order)
                                <tr>
                                    <td class="px-3"><strong class="font-monospace text-primary">#{{ $order->order_code }}</strong></td>
                                    <td class="small">{{ $order->created_at->format('d/m/Y') }}</td>
                                    <td class="fw-bold">{{ number_format($order->total_amount, 0, ',', '.') }} ₫</td>
                                    <td><span class="badge {{ $order->status_badge_class }}">{{ $order->status_label }}</span></td>
                                    <td class="text-end px-3">
                                        <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-outline-primary btn-action">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">Khách hàng chưa đặt đơn nào.</td>
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
    </div>
</div>
@endsection
