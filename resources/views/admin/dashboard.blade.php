@extends('layouts.admin')

@section('title', 'Dashboard Quản Trị Hệ Thống')

@section('content')
<!-- Page Heading -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Bảng Điều Khiển Hệ Thống</h4>
        <p class="text-muted small mb-0">Tổng quan hoạt động kinh doanh của Sport Store hôm nay</p>
    </div>
    <span class="badge bg-primary px-3 py-2 fs-6">
        <i class="fa-solid fa-calendar-day me-1"></i> {{ date('d/m/Y') }}
    </span>
</div>

<!-- KPI Cards Row 1: Revenue & Orders -->
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="card p-3 h-100 border-start border-4 border-primary">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <span class="text-muted small text-uppercase fw-bold">Tổng Doanh Thu</span>
                    <h4 class="fw-extrabold text-primary mb-0 mt-1">{{ number_format($kpis['total_revenue'], 0, ',', '.') }} ₫</h4>
                </div>
                <div class="rounded-3 bg-primary-subtle text-primary p-3">
                    <i class="fa-solid fa-coins fs-4"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card p-3 h-100 border-start border-4 border-success">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <span class="text-muted small text-uppercase fw-bold">Doanh Thu Hôm Nay</span>
                    <h4 class="fw-extrabold text-success mb-0 mt-1">{{ number_format($kpis['today_revenue'], 0, ',', '.') }} ₫</h4>
                </div>
                <div class="rounded-3 bg-success-subtle text-success p-3">
                    <i class="fa-solid fa-sack-dollar fs-4"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card p-3 h-100 border-start border-4 border-info">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <span class="text-muted small text-uppercase fw-bold">Tổng Đơn Hàng</span>
                    <h4 class="fw-extrabold text-info mb-0 mt-1">{{ $kpis['total_orders'] }}</h4>
                </div>
                <div class="rounded-3 bg-info-subtle text-info p-3">
                    <i class="fa-solid fa-receipt fs-4"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card p-3 h-100 border-start border-4 border-warning">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <span class="text-muted small text-uppercase fw-bold">Đơn Chờ Xử Lý</span>
                    <h4 class="fw-extrabold text-warning mb-0 mt-1">{{ $kpis['pending_orders'] }}</h4>
                </div>
                <div class="rounded-3 bg-warning-subtle text-warning p-3">
                    <i class="fa-solid fa-clock fs-4"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- KPI Cards Row 2: Catalog & Stock -->
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="card p-3 h-100">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <span class="text-muted small fw-bold">Tổng Sản Phẩm</span>
                    <h4 class="fw-bold mb-0 mt-1">{{ $kpis['total_products'] }}</h4>
                </div>
                <div class="rounded-3 bg-light text-secondary p-3">
                    <i class="fa-solid fa-box-open fs-4"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card p-3 h-100">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <span class="text-muted small fw-bold">Tổng Khách Hàng</span>
                    <h4 class="fw-bold mb-0 mt-1">{{ $kpis['total_customers'] }}</h4>
                </div>
                <div class="rounded-3 bg-light text-secondary p-3">
                    <i class="fa-solid fa-users fs-4"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card p-3 h-100 border-start border-4 border-danger">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <span class="text-danger small fw-bold">Sắp Hết Hàng (≤ 5)</span>
                    <h4 class="fw-bold text-danger mb-0 mt-1">{{ $kpis['low_stock_products'] }}</h4>
                </div>
                <div class="rounded-3 bg-danger-subtle text-danger p-3">
                    <i class="fa-solid fa-triangle-exclamation fs-4"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card p-3 h-100 border-start border-4 border-dark">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <span class="text-dark small fw-bold">Đã Hết Hàng (0)</span>
                    <h4 class="fw-bold text-dark mb-0 mt-1">{{ $kpis['out_of_stock_products'] }}</h4>
                </div>
                <div class="rounded-3 bg-dark-subtle text-dark p-3">
                    <i class="fa-solid fa-ban fs-4"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="row g-4 mb-4">
    <!-- 7-day Revenue Line Chart -->
    <div class="col-lg-8">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="fa-solid fa-chart-line text-primary me-2"></i> Doanh Thu 7 Ngày Gần Nhất</span>
                <span class="badge bg-light text-secondary">VNĐ</span>
            </div>
            <div class="card-body">
                <canvas id="revenue7DaysChart" style="max-height: 280px;"></canvas>
            </div>
        </div>
    </div>

    <!-- Order Status Doughnut Chart -->
    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-header">
                <i class="fa-solid fa-chart-pie text-warning me-2"></i> Tỷ Lệ Đơn Hàng Theo Trạng Thái
            </div>
            <div class="card-body d-flex align-items-center justify-content-center">
                <canvas id="ordersStatusChart" style="max-height: 260px;"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- 12 Months Revenue Bar Chart -->
<div class="card mb-4">
    <div class="card-header">
        <i class="fa-solid fa-chart-simple text-success me-2"></i> Doanh Thu 12 Tháng Qua
    </div>
    <div class="card-body">
        <canvas id="revenue12MonthsChart" style="max-height: 280px;"></canvas>
    </div>
</div>

<!-- Tables: Recent Orders & Top 10 Best Sellers -->
<div class="row g-4">
    <!-- Recent Orders -->
    <div class="col-lg-7">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="fa-solid fa-cart-shopping text-primary me-2"></i> Đơn Hàng Mới Nhất</span>
                <a href="{{ route('admin.orders.index') }}" class="small text-decoration-none">Xem tất cả</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th class="px-3">Mã đơn</th>
                                <th>Khách hàng</th>
                                <th>Tổng tiền</th>
                                <th>Trạng thái</th>
                                <th class="text-end px-3">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentOrders as $order)
                                <tr>
                                    <td class="px-3"><strong class="font-monospace text-primary">#{{ $order->order_code }}</strong></td>
                                    <td>
                                        <div class="fw-semibold">{{ $order->customer_name }}</div>
                                        <small class="text-muted">{{ $order->customer_phone }}</small>
                                    </td>
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
                                    <td colspan="5" class="text-center py-4 text-muted">Chưa có đơn hàng nào phát sinh.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Top 10 Best Selling Products -->
    <div class="col-lg-5">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="fa-solid fa-fire text-danger me-2"></i> Top Sản Phẩm Bán Chạy</span>
                <a href="{{ route('admin.products.index') }}" class="small text-decoration-none">Quản lý kho</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th class="px-3">Sản phẩm</th>
                                <th class="text-center">Tồn kho</th>
                                <th class="text-end px-3">Đã bán</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($topProducts as $p)
                                <tr>
                                    <td class="px-3">
                                        <div class="fw-semibold text-truncate" style="max-width: 200px;" title="{{ $p->name }}">{{ $p->name }}</div>
                                        <small class="text-muted">{{ $p->brand->name ?? '' }}</small>
                                    </td>
                                    <td class="text-center font-monospace">{{ $p->quantity }}</td>
                                    <td class="text-end px-3 fw-bold text-success">{{ $p->sold }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // 1. Revenue 7 Days Chart
    const ctx7Days = document.getElementById('revenue7DaysChart').getContext('2d');
    new Chart(ctx7Days, {
        type: 'line',
        data: {
            labels: {!! json_encode($revenue7Days['labels']) !!},
            datasets: [{
                label: 'Doanh thu (VNĐ)',
                data: {!! json_encode($revenue7Days['data']) !!},
                borderColor: '#ff5722',
                backgroundColor: 'rgba(255, 87, 34, 0.1)',
                fill: true,
                tension: 0.3,
                borderWidth: 2,
                pointBackgroundColor: '#ff5722'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return value.toLocaleString('vi-VN') + ' đ';
                        }
                    }
                }
            }
        }
    });

    // 2. Orders Status Doughnut Chart
    const ctxStatus = document.getElementById('ordersStatusChart').getContext('2d');
    new Chart(ctxStatus, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($ordersByStatus['labels']) !!},
            datasets: [{
                data: {!! json_encode($ordersByStatus['data']) !!},
                backgroundColor: ['#ffc107', '#0dcaf0', '#0d6efd', '#6c757d', '#198754', '#dc3545']
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });

    // 3. Revenue 12 Months Chart
    const ctx12Months = document.getElementById('revenue12MonthsChart').getContext('2d');
    new Chart(ctx12Months, {
        type: 'bar',
        data: {
            labels: {!! json_encode($revenue12Months['labels']) !!},
            datasets: [{
                label: 'Doanh thu tháng (VNĐ)',
                data: {!! json_encode($revenue12Months['data']) !!},
                backgroundColor: '#10b981',
                borderRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return value.toLocaleString('vi-VN') + ' đ';
                        }
                    }
                }
            }
        }
    });
</script>
@endpush
