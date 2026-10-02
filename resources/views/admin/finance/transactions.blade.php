@extends('layouts.admin')

@section('title', 'Giao Dịch Thanh Toán')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h3 class="fw-bold mb-0">Giao dịch thanh toán</h3>
            <p class="text-muted small mb-0">Tra cứu thanh toán theo đơn hàng và cập nhật trạng thái COD.</p>
        </div>
    </div>

    <!-- Nav Pills -->
    <ul class="nav nav-pills my-3" aria-label="Tài chính">
        <li class="nav-item">
            <a class="nav-link rounded-pill px-4 fw-bold text-dark" href="{{ route('admin.finance.index') }}">
                <i class="fa-solid fa-chart-pie me-1"></i> Thống kê chỉ số
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link active rounded-pill px-4 fw-bold" aria-current="page" href="{{ route('admin.finance.transactions') }}">
                <i class="fa-solid fa-receipt me-1"></i> Giao dịch thanh toán
            </a>
        </li>
    </ul>

    <!-- Filter Form -->
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-3">
            <form action="{{ route('admin.finance.transactions') }}" method="GET" class="row g-2">
                <div class="col-lg-4 col-md-6">
                    <label class="form-label small text-muted mb-1">Tìm đơn hàng</label>
                    <input type="text" name="search" class="form-control form-control-sm" placeholder="Mã đơn, tên hoặc số điện thoại" value="{{ $filters['search'] ?? '' }}">
                </div>
                <div class="col-lg-2 col-md-3 col-6">
                    <label class="form-label small text-muted mb-1">Từ ngày tạo đơn</label>
                    <input type="date" name="date_from" class="form-control form-control-sm" value="{{ $filters['date_from'] ?? '' }}">
                </div>
                <div class="col-lg-2 col-md-3 col-6">
                    <label class="form-label small text-muted mb-1">Đến ngày tạo đơn</label>
                    <input type="date" name="date_to" class="form-control form-control-sm" value="{{ $filters['date_to'] ?? '' }}">
                </div>
                <div class="col-lg-2 col-md-3 col-6">
                    <label class="form-label small text-muted mb-1">Số tiền từ (đ)</label>
                    <input type="number" name="min_amount" class="form-control form-control-sm" placeholder="Không giới hạn" value="{{ $filters['min_amount'] ?? '' }}">
                </div>
                <div class="col-lg-2 col-md-3 col-6">
                    <label class="form-label small text-muted mb-1">Số tiền đến (đ)</label>
                    <input type="number" name="max_amount" class="form-control form-control-sm" placeholder="Không giới hạn" value="{{ $filters['max_amount'] ?? '' }}">
                </div>

                <div class="col-lg-3 col-md-6">
                    <label class="form-label small text-muted mb-1">Phương thức</label>
                    <select name="gateway" class="form-select form-select-sm">
                        <option value="">Tất cả</option>
                        @foreach($methods as $key => $lbl)
                            <option value="{{ $key }}" {{ ($filters['gateway'] ?? '') === $key ? 'selected' : '' }}>{{ $lbl }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-3 col-md-6">
                    <label class="form-label small text-muted mb-1">Trạng thái thanh toán</label>
                    <select name="payment_status" class="form-select form-select-sm">
                        <option value="">Tất cả</option>
                        @foreach($statuses as $stKey => $stLbl)
                            <option value="{{ $stKey }}" {{ ($filters['payment_status'] ?? '') === $stKey ? 'selected' : '' }}>{{ $stLbl }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-3 col-md-6">
                    <label class="form-label small text-muted mb-1">Sắp xếp</label>
                    <select name="sort" class="form-select form-select-sm">
                        <option value="newest" {{ ($filters['sort'] ?? 'newest') === 'newest' ? 'selected' : '' }}>Mới nhất</option>
                        <option value="oldest" {{ ($filters['sort'] ?? '') === 'oldest' ? 'selected' : '' }}>Cũ nhất</option>
                        <option value="amount_asc" {{ ($filters['sort'] ?? '') === 'amount_asc' ? 'selected' : '' }}>Số tiền tăng dần</option>
                        <option value="amount_desc" {{ ($filters['sort'] ?? '') === 'amount_desc' ? 'selected' : '' }}>Số tiền giảm dần</option>
                    </select>
                </div>
                <div class="col-lg-3 col-md-6 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-sm btn-primary rounded-pill px-4">
                        <i class="fa-solid fa-filter me-1"></i> Áp dụng bộ lọc
                    </button>
                    <a href="{{ route('admin.finance.transactions') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                        Xóa bộ lọc
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-3" role="alert">
            <i class="fa-solid fa-check-circle me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show rounded-3" role="alert">
            <i class="fa-solid fa-triangle-exclamation me-1"></i> {{ $errors->first() }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="small text-muted mb-3">
        Có <strong>{{ $orders->total() }}</strong> đơn phù hợp bộ lọc. Số tiền bao gồm phí vận chuyển; ngày lọc là ngày tạo đơn.
    </div>

    <!-- Transactions Table -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
        <div class="card-header bg-white py-3 border-0">
            <h5 class="fw-bold mb-0">Danh sách giao dịch ({{ $orders->total() }} đơn)</h5>
            <small class="text-muted">COD: xác nhận thu tiền hoặc thất bại; đơn đã thu tiền có thể chuyển sang chờ hoàn tiền rồi xác nhận đã hoàn tiền.</small>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Đơn hàng</th>
                        <th>Khách hàng</th>
                        <th>Phương thức</th>
                        <th class="text-end">Số tiền</th>
                        <th class="text-center">Thanh toán</th>
                        <th class="pe-4" style="min-width: 240px;">Cập nhật COD</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        @php
                            $isCod = in_array(strtolower($order->gateway ?? ''), ['cod']) || in_array(strtolower($order->payment_method ?? ''), ['cod']);
                            $currentPayStatus = $order->payment_id ? ($order->payment_status ?? 'pending') : ($order->payment_status === 'paid' ? 'paid' : 'pending');
                            if (!array_key_exists($currentPayStatus, $statuses)) {
                                $currentPayStatus = 'pending';
                            }
                            $allowedTransitions = $codTransitions[$currentPayStatus] ?? [$currentPayStatus];
                            $isCancelledOrReturned = ($order->status === 'cancelled') || in_array($order->shipping_status ?? '', ['cancelled', 'return', 'returned']);
                        @endphp
                        <tr>
                            <td class="ps-4">
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="fw-bold text-primary text-decoration-none">
                                    #{{ $order->order_code ?? $order->id }}
                                </a>
                                <div class="text-muted small">
                                    <i class="fa-regular fa-clock me-1"></i>{{ \Carbon\Carbon::parse($order->created_at)->format('d/m/Y H:i') }}
                                </div>
                            </td>
                            <td>
                                <div class="fw-bold">{{ $order->customer_name }}</div>
                                <div class="text-muted small">{{ $order->customer_phone }}</div>
                            </td>
                            <td>
                                @if(strtolower($order->gateway ?? $order->payment_method) === 'momo')
                                    <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-2 py-1">
                                        <i class="fa-solid fa-mobile-screen me-1"></i> MoMo
                                    </span>
                                @elseif(strtolower($order->gateway ?? $order->payment_method) === 'cod')
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 px-2 py-1">
                                        <i class="fa-solid fa-money-bill-wave me-1"></i> COD
                                    </span>
                                @else
                                    <span class="badge bg-light text-dark border px-2 py-1">
                                        {{ $methods[$order->gateway] ?? $order->gateway ?? 'Khác' }}
                                    </span>
                                @endif
                            </td>
                            <td class="text-end fw-bold text-dark">
                                {{ number_format($order->total_amount, 0, ',', '.') }} đ
                            </td>
                            <td class="text-center">
                                @php
                                    $badgeClass = match($order->payment_status) {
                                        'paid' => 'bg-success text-white',
                                        'pending', 'initiated' => 'bg-warning text-dark',
                                        'failed' => 'bg-danger text-white',
                                        'refund_pending' => 'bg-info text-dark',
                                        'refunded' => 'bg-secondary text-white',
                                        'cancelled' => 'bg-dark text-white',
                                        default => 'bg-secondary text-white',
                                    };
                                @endphp
                                <span class="badge {{ $badgeClass }} px-2 py-1">
                                    {{ $statuses[$order->payment_status] ?? $order->payment_status }}
                                </span>
                                @if($order->paid_at)
                                    <div class="text-muted small" style="font-size: 0.72rem;">
                                        {{ \Carbon\Carbon::parse($order->paid_at)->format('d/m H:i') }}
                                    </div>
                                @endif
                            </td>
                            <td class="pe-4">
                                @if($isCod)
                                    <form action="{{ route('admin.finance.update-status', $order->id) }}" method="POST" class="d-flex align-items-center gap-1">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="current_payment_status" value="{{ $currentPayStatus }}">
                                        <input type="hidden" name="current_order_status" value="{{ $order->status }}">
                                        <input type="hidden" name="current_payment_id" value="{{ (int)($order->payment_id ?? 0) }}">

                                        <select name="payment_status" class="form-select form-select-sm" style="font-size: 0.85rem;" {{ $isCancelledOrReturned ? 'disabled' : '' }}>
                                            @foreach($allowedTransitions as $transKey)
                                                <option value="{{ $transKey }}" {{ $transKey === $currentPayStatus ? 'selected' : '' }}>
                                                    {{ $statuses[$transKey] ?? $transKey }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <button type="submit" class="btn btn-sm btn-outline-primary px-2" {{ $isCancelledOrReturned ? 'disabled' : '' }} title="Cập nhật trạng thái COD">
                                            <i class="fa-solid fa-floppy-disk"></i>
                                        </button>
                                    </form>
                                    @if($isCancelledOrReturned)
                                        <small class="text-danger d-block mt-1" style="font-size: 0.75rem;">
                                            <i class="fa-solid fa-circle-exclamation me-1"></i> Đơn đã hủy / hoàn hàng
                                        </small>
                                    @endif
                                @else
                                    <span class="text-muted small fst-italic">
                                        <i class="fa-solid fa-shield-halved me-1 text-success"></i> Tự động qua cổng
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="fa-solid fa-inbox fa-3x mb-3 text-secondary opacity-50 d-block"></i>
                                Không có đơn hàng phù hợp với bộ lọc.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($orders->hasPages())
            <div class="card-footer bg-white py-3 border-0 d-flex justify-content-end">
                {{ $orders->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
</div>
@endsection
