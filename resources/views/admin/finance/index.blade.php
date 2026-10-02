@extends('layouts.admin')

@section('title', 'Thống Kê Tài Chính')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h3 class="fw-bold mb-0">Thống Kê Tài Chính</h3>
            <p class="text-muted small mb-0">Tổng hợp giá trị thanh toán theo trạng thái và phương thức thanh toán.</p>
        </div>
    </div>

    <!-- Nav Pills -->
    <ul class="nav nav-pills my-3" aria-label="Tài chính">
        <li class="nav-item">
            <a class="nav-link active rounded-pill px-4 fw-bold" aria-current="page" href="{{ route('admin.finance.index') }}">
                <i class="fa-solid fa-chart-pie me-1"></i> Thống kê chỉ số
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link rounded-pill px-4 fw-bold text-dark" href="{{ route('admin.finance.transactions') }}">
                <i class="fa-solid fa-receipt me-1"></i> Giao dịch thanh toán
            </a>
        </li>
    </ul>

    <!-- Filters -->
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-3">
            <form action="{{ route('admin.finance.index') }}" method="GET" class="row g-2">
                <div class="col-lg-4 col-md-6">
                    <label class="form-label small text-muted mb-1">Tìm đơn hàng</label>
                    <input type="text" name="search" class="form-control form-control-sm" placeholder="Mã đơn, tên hoặc số điện thoại..." value="{{ $filters['search'] ?? '' }}">
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
                    <label class="form-label small text-muted mb-1">Số tiền từ (₫)</label>
                    <input type="number" name="min_amount" class="form-control form-control-sm" placeholder="Không giới hạn" value="{{ $filters['min_amount'] ?? '' }}">
                </div>
                <div class="col-lg-2 col-md-3 col-6">
                    <label class="form-label small text-muted mb-1">Số tiền đến (₫)</label>
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
                <div class="col-lg-6 col-md-12 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-sm btn-primary rounded-pill px-4">
                        <i class="fa-solid fa-filter me-1"></i> Áp dụng bộ lọc
                    </button>
                    <a href="{{ route('admin.finance.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                        Xóa bộ lọc
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="small text-muted mb-3">
        Có <strong>{{ $summary->order_count ?? 0 }}</strong> đơn phù hợp. Số tiền bao gồm phí vận chuyển, thống kê theo ngày tạo đơn trên toàn bộ kết quả lọc.
    </div>

    <!-- 8 Summary Cards (Chuẩn Lab Finance) -->
    <div class="row g-3 mb-4">
        @php
            $statBoxes = [
                ['total', 'Tổng giá trị đơn hàng', $summary->total_amount ?? 0, ($summary->order_count ?? 0) . ' đơn, bao gồm đơn đã hủy', 'primary'],
                ['pending', 'Chờ thanh toán', $statusTotals->get('pending')?->total_amount ?? 0, ($statusTotals->get('pending')?->order_count ?? 0) . ' đơn', 'secondary'],
                ['initiated', 'Đang chờ MoMo', $statusTotals->get('initiated')?->total_amount ?? 0, ($statusTotals->get('initiated')?->order_count ?? 0) . ' đơn', 'info'],
                ['paid', 'Đã thanh toán', $statusTotals->get('paid')?->total_amount ?? 0, ($statusTotals->get('paid')?->order_count ?? 0) . ' đơn', 'success'],
                ['failed', 'Thanh toán thất bại', $statusTotals->get('failed')?->total_amount ?? 0, ($statusTotals->get('failed')?->order_count ?? 0) . ' đơn', 'danger'],
                ['cancelled', 'Đã hủy', $statusTotals->get('cancelled')?->total_amount ?? 0, ($statusTotals->get('cancelled')?->order_count ?? 0) . ' đơn', 'dark'],
                ['refund_pending', 'Chờ hoàn tiền', $statusTotals->get('refund_pending')?->total_amount ?? 0, ($statusTotals->get('refund_pending')?->order_count ?? 0) . ' đơn', 'warning'],
                ['refunded', 'Đã hoàn tiền', $statusTotals->get('refunded')?->total_amount ?? 0, ($statusTotals->get('refunded')?->order_count ?? 0) . ' đơn', 'secondary'],
            ];
        @endphp

        @foreach($statBoxes as [$key, $title, $amount, $subtext, $color])
            <div class="col-xl-3 col-lg-4 col-md-6">
                <div class="card border-0 shadow-sm rounded-4 p-3 h-100">
                    <span class="text-muted small fw-semibold">{{ $title }}</span>
                    <h4 class="mb-1 fw-bold text-{{ $color }} mt-2">
                        {{ number_format($amount, 0, ',', '.') }} ₫
                    </h4>
                    <span class="text-muted small" style="font-size: 0.75rem;">{{ $subtext }}</span>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Bảng Thống Kê Theo Phương Thức -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
        <div class="card-header bg-white py-3 px-4 fw-bold">
            <i class="fa-solid fa-credit-card me-2 text-primary"></i> Thống kê theo phương thức
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="px-4 py-3">Phương thức</th>
                        <th class="text-center py-3">Số đơn</th>
                        <th class="text-end py-3">Tổng giá trị</th>
                        <th class="text-end px-4 py-3">Đã thanh toán</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($methods as $mKey => $mLabel)
                        @php
                            $row = $methodTotals->get($mKey);
                        @endphp
                        <tr>
                            <td class="px-4 fw-semibold">
                                @if($mKey === 'momo')
                                    <span class="badge text-white px-2 py-1 me-1" style="background-color: #a50064;">MoMo</span>
                                @elseif($mKey === 'cod')
                                    <span class="badge bg-light text-dark border px-2 py-1 me-1">COD</span>
                                @endif
                                {{ $mLabel }}
                            </td>
                            <td class="text-center font-monospace">{{ number_format($row?->order_count ?? 0) }}</td>
                            <td class="text-end fw-bold">{{ number_format($row?->total_amount ?? 0, 0, ',', '.') }} ₫</td>
                            <td class="text-end px-4 fw-bold text-success">{{ number_format($row?->paid_amount ?? 0, 0, ',', '.') }} ₫</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
