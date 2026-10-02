@extends('layouts.admin')

@section('title', 'Báo Cáo Doanh Thu')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h3 class="fw-bold mb-0">Báo Cáo Doanh Thu</h3>
            <p class="text-muted small mb-0">Doanh thu tính theo ngày tạo đơn, chỉ gồm đơn đã thanh toán, chưa hoàn tiền và không bị hủy hoặc hoàn hàng.</p>
        </div>
    </div>

    <!-- Nav Pills Chuyển Đổi Bảng Số Liệu / Biểu Đồ (Chuẩn Lab Báo Cáo) -->
    <ul class="nav nav-pills my-3" aria-label="Báo cáo">
        <li class="nav-item">
            <a class="nav-link active rounded-pill px-4 fw-bold" aria-current="page" href="{{ route('admin.reports.index') }}">
                <i class="fa-solid fa-table me-1"></i> Bảng số liệu
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link rounded-pill px-4 fw-bold text-dark" href="{{ route('admin.reports.charts') }}">
                <i class="fa-solid fa-chart-line me-1"></i> Biểu đồ
            </a>
        </li>
    </ul>

    <!-- KPI Summary Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 p-4 h-100">
                <span class="text-muted small fw-semibold text-uppercase">Tổng số đơn hàng</span>
                <h2 class="mb-0 fw-extrabold text-primary mt-2">{{ number_format($totalOrders) }}</h2>
                <small class="text-muted mt-1">Bao gồm toàn bộ đơn đã tạo trên hệ thống</small>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 p-4 h-100">
                <span class="text-muted small fw-semibold text-uppercase">Tổng số khách hàng</span>
                <h2 class="mb-0 fw-extrabold text-info mt-2">{{ number_format($totalCustomers) }}</h2>
                <small class="text-muted mt-1">Khách hàng đã đăng ký tài khoản</small>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 p-4 h-100">
                <span class="text-muted small fw-semibold text-uppercase">Tổng doanh thu (Đã thu tiền)</span>
                <h2 class="mb-0 fw-extrabold text-success mt-2">{{ number_format($totalRevenue, 0, ',', '.') }} ₫</h2>
                <small class="text-muted mt-1">Đã cộng phí vận chuyển thực tế</small>
            </div>
        </div>
    </div>

    <!-- Doanh thu theo danh mục -->
    <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
        <div class="card-header bg-white py-3 px-4">
            <h5 class="fw-bold mb-0">Doanh thu theo danh mục</h5>
            <div class="small text-muted">Tính theo giá sản phẩm khi đặt hàng, không gồm phí vận chuyển.</div>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="px-4 py-3">Danh mục</th>
                        <th class="text-end py-3">Số lượng bán</th>
                        <th class="text-end px-4 py-3">Doanh thu</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categoryRevenue as $revenue)
                        <tr>
                            <td class="px-4 fw-semibold">{{ $revenue->category_name ?? ('Danh mục #' . $revenue->category_id) }}</td>
                            <td class="text-end font-monospace">{{ number_format($revenue->total_qty) }}</td>
                            <td class="text-end px-4 fw-bold text-success">{{ number_format($revenue->total_revenue, 0, ',', '.') }} ₫</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted py-4">Chưa có doanh thu.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Bảng doanh thu theo ngày, tháng, năm -->
    @foreach([
        ['Doanh thu theo ngày (Gần đây)', 'Ngày', 'date', $revenueByDate, 'd/m/Y'],
        ['Doanh thu theo tháng', 'Tháng', 'month', $revenueByMonth, 'm/Y'],
        ['Doanh thu theo năm', 'Năm', 'year', $revenueByYear, null],
    ] as [$title, $label, $field, $rows, $format])
        <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
            <div class="card-header bg-white py-3 px-4 fw-bold">
                {{ $title }}
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="px-4 py-3">{{ $label }}</th>
                            <th class="text-end py-3">Số đơn đã thanh toán</th>
                            <th class="text-end px-4 py-3">Doanh thu</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rows as $revenue)
                            <tr>
                                <td class="px-4 fw-semibold">
                                    {{ $format ? \Carbon\Carbon::parse($revenue->{$field} . ($field === 'month' ? '-01' : ''))->format($format) : $revenue->{$field} }}
                                </td>
                                <td class="text-end font-monospace">{{ number_format($revenue->order_count) }}</td>
                                <td class="text-end px-4 fw-bold text-success">{{ number_format($revenue->total_revenue, 0, ',', '.') }} ₫</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted py-4">Chưa có doanh thu.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endforeach
</div>
@endsection
