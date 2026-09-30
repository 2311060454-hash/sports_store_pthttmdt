@extends('layouts.admin')

@section('title', 'Quản Lý Khách Hàng')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Khách Hàng Hệ Thống</h4>
        <p class="text-muted small mb-0">Quản lý tài khoản, trạng thái hoạt động và lịch sử tiêu dùng</p>
    </div>
</div>

<div class="card mb-4">
    <div class="card-body p-3">
        <form action="{{ route('admin.customers.index') }}" method="GET" class="row g-2 align-items-center">
            <div class="col-md-4">
                <input type="text" name="q" class="form-control form-control-sm" placeholder="Tìm tên, email, số điện thoại..." value="{{ request('q') }}">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-sm btn-primary w-100 rounded-pill">Tìm kiếm</button>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="px-3">Khách hàng</th>
                        <th>Số điện thoại</th>
                        <th>Ngày đăng ký</th>
                        <th class="text-center">Số đơn hàng</th>
                        <th>Tổng chi tiêu</th>
                        <th>Trạng thái</th>
                        <th class="text-end px-3">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($customers as $customer)
                        <tr>
                            <td class="px-3">
                                <div class="d-flex align-items-center">
                                    @if($customer->avatar)
                                        <img src="{{ asset($customer->avatar) }}" class="rounded-circle border me-2" style="width: 40px; height: 40px; object-fit: cover;">
                                    @else
                                        <div class="rounded-circle bg-warning-subtle text-warning fw-bold d-flex align-items-center justify-content-center me-2" style="width: 40px; height: 40px;">
                                            {{ strtoupper(substr($customer->name, 0, 1)) }}
                                        </div>
                                    @endif
                                    <div>
                                        <div class="fw-bold">{{ $customer->name }}</div>
                                        <small class="text-muted">{{ $customer->email }}</small>
                                    </div>
                                </div>
                            </td>
                            <td class="font-monospace small">{{ $customer->phone ?? '-' }}</td>
                            <td class="small">{{ $customer->created_at->format('d/m/Y') }}</td>
                            <td class="text-center">
                                <span class="badge bg-light text-dark border">{{ $customer->orders_count }} đơn</span>
                            </td>
                            <td class="fw-bold text-success">
                                {{ number_format($customer->orders_sum_total_amount ?? 0, 0, ',', '.') }} ₫
                            </td>
                            <td>
                                <span class="badge {{ $customer->status === 'active' ? 'bg-success' : 'bg-danger' }}">
                                    {{ $customer->status === 'active' ? 'Hoạt động' : 'Đã khóa' }}
                                </span>
                            </td>
                            <td class="text-end px-3">
                                <a href="{{ route('admin.customers.show', $customer->id) }}" class="btn btn-sm btn-outline-primary btn-action me-1" title="Xem chi tiết">
                                    <i class="fa-solid fa-eye"></i>
                                </a>

                                <form action="{{ route('admin.customers.toggle-status', $customer->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-sm btn-outline-{{ $customer->status === 'active' ? 'warning' : 'success' }} btn-action" title="{{ $customer->status === 'active' ? 'Khóa tài khoản' : 'Mở khóa tài khoản' }}">
                                        <i class="fa-solid {{ $customer->status === 'active' ? 'fa-lock' : 'fa-lock-open' }}"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">Không tìm thấy khách hàng nào.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($customers->hasPages())
        <div class="card-footer bg-white py-3 d-flex justify-content-center">
            {{ $customers->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>
@endsection
