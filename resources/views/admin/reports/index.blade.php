@extends('layouts.admin')

@section('title', 'Báo Cáo Doanh Thu')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Báo Cáo Hoạt Động & Doanh Thu</h4>
        <p class="text-muted small mb-0">Thống kê chi tiết doanh thu theo danh mục và thời gian</p>
    </div>
    <button type="button" class="btn btn-outline-secondary rounded-pill px-3" onclick="window.print()">
        <i class="fa-solid fa-print me-1"></i> In Báo Cáo
    </button>
</div>

<!-- Category Revenue Breakdown -->
<div class="row g-4 mb-4">
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header bg-white py-3">
                <h5 class="fw-bold mb-0"><i class="fa-solid fa-layer-group text-primary me-2"></i> Doanh Thu Theo Từng Danh Mục Môn Thể Thao</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th class="px-3">Danh mục</th>
                                <th class="text-end px-3">Tổng doanh thu</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($categoryRevenue as $item)
                                <tr>
                                    <td class="px-3"><strong>{{ $item->name }}</strong></td>
                                    <td class="text-end px-3 fw-bold text-success">
                                        {{ number_format($item->total_revenue, 0, ',', '.') }} ₫
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="text-center py-4 text-muted">Chưa có dữ liệu bán hàng.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Order status distribution -->
    <div class="col-lg-6">
        <div class="card h-100 p-4 text-center">
            <h5 class="fw-bold mb-3 text-start"><i class="fa-solid fa-chart-pie text-warning me-2"></i> Cơ Cấu Đơn Hàng</h5>
            <canvas id="reportStatusChart" style="max-height: 260px;"></canvas>
        </div>
    </div>
</div>

<!-- Revenue 12 Months -->
<div class="card">
    <div class="card-header bg-white py-3">
        <h5 class="fw-bold mb-0"><i class="fa-solid fa-chart-column text-success me-2"></i> Xu Hướng Doanh Thu 12 Tháng Qua</h5>
    </div>
    <div class="card-body">
        <canvas id="report12MonthsChart" style="max-height: 320px;"></canvas>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // 1. Status Chart
    new Chart(document.getElementById('reportStatusChart'), {
        type: 'pie',
        data: {
            labels: {!! json_encode($ordersByStatus['labels']) !!},
            datasets: [{
                data: {!! json_encode($ordersByStatus['data']) !!},
                backgroundColor: ['#ffc107', '#0dcaf0', '#0d6efd', '#6c757d', '#198754', '#dc3545']
            }]
        }
    });

    // 2. 12 Months Chart
    new Chart(document.getElementById('report12MonthsChart'), {
        type: 'bar',
        data: {
            labels: {!! json_encode($revenue12Months['labels']) !!},
            datasets: [{
                label: 'Doanh thu (VNĐ)',
                data: {!! json_encode($revenue12Months['data']) !!},
                backgroundColor: '#ff5722',
                borderRadius: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(val) {
                            return val.toLocaleString('vi-VN') + ' đ';
                        }
                    }
                }
            }
        }
    });
</script>
@endpush
