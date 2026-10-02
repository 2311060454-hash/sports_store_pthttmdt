@extends('layouts.admin')

@section('title', 'Thông Tin Người Dùng #' . $user->id)

@section('content')
<div class="mb-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-1 small">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none text-muted">Quản trị</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}" class="text-decoration-none text-muted">Người dùng</a></li>
            <li class="breadcrumb-item active text-primary fw-semibold" aria-current="page">Chi tiết #{{ $user->id }}</li>
        </ol>
    </nav>
    <div class="d-flex justify-content-between align-items-center">
        <h4 class="fw-bold mb-0">Thông Tin Người Dùng</h4>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                <i class="fa-solid fa-pen-to-square me-1"></i> Sửa thông tin
            </a>
            <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-light rounded-pill px-3">
                ← Quay lại danh sách
            </a>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 p-4 text-center">
            <div class="rounded-circle bg-primary-subtle text-primary d-inline-flex align-items-center justify-content-center mx-auto mb-3" style="width: 80px; height: 80px; font-size: 2rem;">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
            <h5 class="fw-bold mb-1">{{ $user->name }}</h5>
            <p class="text-muted small mb-2">{{ $user->email }}</p>
            <div>
                @if($user->isAdmin())
                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-3 py-1">Quản trị viên (Admin)</span>
                @else
                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-1">Khách hàng (User)</span>
                @endif
            </div>

            <hr class="my-3">

            <div class="d-flex justify-content-around text-center">
                <div>
                    <h5 class="fw-bold mb-0 text-primary">{{ $user->orders_count ?? 0 }}</h5>
                    <small class="text-muted">Đơn hàng</small>
                </div>
                <div>
                    <h5 class="fw-bold mb-0 text-success">{{ $user->created_at->format('d/m/Y') }}</h5>
                    <small class="text-muted">Ngày tham gia</small>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4 p-4">
            <h5 class="fw-bold mb-3"><i class="fa-solid fa-clock-rotate-left me-2 text-primary"></i> Đơn hàng gần đây</h5>
            @if(isset($orders) && $orders->count() > 0)
                <div class="table-responsive">
                    <table class="table table-sm align-middle mb-0">
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
                            @foreach($orders as $o)
                                <tr>
                                    <td><strong class="font-monospace text-primary">#{{ $o->order_code }}</strong></td>
                                    <td class="small">{{ $o->created_at->format('d/m/Y H:i') }}</td>
                                    <td class="fw-bold">{{ number_format($o->total_amount, 0, ',', '.') }} ₫</td>
                                    <td>
                                        <span class="badge {{ $o->status_badge_class }}">
                                            {{ $o->status_label }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('admin.orders.show', $o->id) }}" class="btn btn-sm btn-outline-primary rounded-pill px-2">Xem</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-muted text-center py-4">Người dùng này chưa đặt đơn hàng nào.</div>
            @endif
        </div>
    </div>
</div>
@endsection
