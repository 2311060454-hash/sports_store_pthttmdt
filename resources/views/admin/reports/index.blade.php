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

    <!-- Top Sản phẩm bán chạy nhất (Yêu cầu Bài tập lớn) -->
    <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
        <div class="card-header bg-white py-3 px-4 d-flex justify-content-between align-items-center">
            <div>
                <h5 class="fw-bold mb-0"><i class="fa-solid fa-fire text-danger me-2"></i> Top 10 Sản phẩm bán chạy nhất</h5>
                <div class="small text-muted">Thống kê theo số lượng sản phẩm đã bán và doanh thu thực tế từ đơn hàng thành công.</div>
            </div>
            <span class="badge bg-danger rounded-pill px-3 py-2">Bán chạy nhất</span>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="px-4 py-3" style="width: 60px;">#</th>
                        <th class="py-3">Sản phẩm</th>
                        <th class="py-3">Danh mục</th>
                        <th class="text-end py-3">Đơn giá</th>
                        <th class="text-end py-3">Đã bán</th>
                        <th class="text-end px-4 py-3">Tổng thu</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bestSellingProducts as $index => $item)
                        <tr>
                            <td class="px-4 fw-bold text-muted">
                                @if($index === 0)
                                    <span class="badge bg-warning text-dark"><i class="fa-solid fa-crown"></i> 1</span>
                                @elseif($index === 1)
                                    <span class="badge bg-secondary text-white">2</span>
                                @elseif($index === 2)
                                    <span class="badge bg-bronze text-white" style="background: #cd7f32;">3</span>
                                @else
                                    {{ $index + 1 }}
                                @endif
                            </td>
                            <td class="py-3">
                                <a href="{{ route('admin.products.edit', $item->id) }}" class="fw-bold text-dark text-decoration-none hover-primary">
                                    {{ $item->name }}
                                </a>
                                @if($item->sku)
                                    <div class="text-muted small">SKU: {{ $item->sku }}</div>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border">{{ $item->category_name ?? 'Chưa phân loại' }}</span>
                            </td>
                            <td class="text-end font-monospace">{{ number_format($item->price, 0, ',', '.') }} ₫</td>
                            <td class="text-end fw-bold text-primary font-monospace">{{ number_format($item->total_sold) }}</td>
                            <td class="text-end px-4 fw-bold text-success font-monospace">{{ number_format($item->total_revenue, 0, ',', '.') }} ₫</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">Chưa có sản phẩm nào có lượt bán từ đơn đã thanh toán.</td>
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
