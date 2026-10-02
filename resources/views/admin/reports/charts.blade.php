@extends('layouts.admin')

@section('title', 'Biểu Đồ Báo Cáo Doanh Thu')

@push('styles')
<style>
    .chart-wrap { min-height: 360px; position: relative; }
    .chart-wrap canvas { width: 100% !important; height: 360px !important; }
</style>
@endpush

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h3 class="fw-bold mb-0">Biểu Đồ Báo Cáo Doanh Thu</h3>
            <p class="text-muted small mb-0">Chỉ gồm đơn đã thanh toán, chưa hoàn tiền và không bị hủy hoặc hoàn hàng. Doanh thu tính theo ngày tạo đơn; số liệu theo danh mục không gồm phí vận chuyển.</p>
        </div>
    </div>

    <!-- Nav Pills Chuyển Đổi Bảng Số Liệu / Biểu Đồ -->
    <ul class="nav nav-pills my-3" aria-label="Báo cáo">
        <li class="nav-item">
            <a class="nav-link rounded-pill px-4 fw-bold text-dark" href="{{ route('admin.reports.index') }}">
                <i class="fa-solid fa-table me-1"></i> Bảng số liệu
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link active rounded-pill px-4 fw-bold" aria-current="page" href="{{ route('admin.reports.charts') }}">
                <i class="fa-solid fa-chart-line me-1"></i> Biểu đồ
            </a>
        </li>
    </ul>

    <div id="report-chart-error" class="alert alert-warning d-none" role="alert">
        Không tải được thư viện biểu đồ. Bạn có thể xem số liệu tại trang <a href="{{ route('admin.reports.index') }}">Bảng số liệu</a>.
    </div>

    <div class="row g-4 mb-4">
        <!-- 1. Doanh thu theo danh mục -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm rounded-4 h-100 p-3">
                <div class="card-header bg-white border-0 fw-bold fs-6">
                    <i class="fa-solid fa-layer-group me-2 text-primary"></i> Doanh thu theo danh mục
                </div>
                <div class="card-body chart-wrap">
                    <canvas id="categoryRevenueChart"></canvas>
                </div>
            </div>
        </div>

        <!-- 2. Doanh thu theo ngày (30 ngày) -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm rounded-4 h-100 p-3">
                <div class="card-header bg-white border-0 fw-bold fs-6">
                    <i class="fa-solid fa-calendar-day me-2 text-info"></i> Doanh thu theo ngày (30 ngày gần nhất)
                </div>
                <div class="card-body chart-wrap">
                    <canvas id="revenueByDateChart"></canvas>
                </div>
            </div>
        </div>

        <!-- 3. Doanh thu theo tháng (12 tháng) -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm rounded-4 h-100 p-3">
                <div class="card-header bg-white border-0 fw-bold fs-6">
                    <i class="fa-solid fa-calendar-week me-2 text-success"></i> Doanh thu theo tháng (12 tháng gần nhất)
                </div>
                <div class="card-body chart-wrap">
                    <canvas id="revenueByMonthChart"></canvas>
                </div>
            </div>
        </div>

        <!-- 4. Doanh thu theo năm -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm rounded-4 h-100 p-3">
                <div class="card-header bg-white border-0 fw-bold fs-6">
                    <i class="fa-solid fa-calendar-check me-2 text-warning"></i> Doanh thu theo năm
                </div>
                <div class="card-body chart-wrap">
                    <canvas id="revenueByYearChart"></canvas>
                </div>
            </div>
        </div>

        <!-- 5. Doanh thu theo phương thức thanh toán -->
        <div class="col-lg-12">
            <div class="card border-0 shadow-sm rounded-4 p-3">
                <div class="card-header bg-white border-0 fw-bold fs-6">
                    <i class="fa-solid fa-wallet me-2 text-danger"></i> Doanh thu theo phương thức thanh toán (MoMo vs COD)
                </div>
                <div class="card-body chart-wrap" style="max-height: 380px;">
                    <canvas id="revenueByPaymentMethodChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Dữ liệu truyền từ Controller sang JS -->
<div id="report-chart-data" hidden data-chart-data="{{ json_encode([
    'catLabels' => $catLabels ?? [],
    'catRevenue' => $catRevenue ?? [],
    'revDateLabels' => $revDateLabels ?? [],
    'revDateData' => $revDateData ?? [],
    'revMonthLabels' => $revMonthLabels ?? [],
    'revMonthData' => $revMonthData ?? [],
    'revYearLabels' => $revYearLabels ?? [],
    'revYearData' => $revYearData ?? [],
    'paymentMethodLabels' => $paymentMethodLabels ?? [],
    'paymentMethodRevenue' => $paymentMethodRevenue ?? [],
]) }}"></div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
window.addEventListener('DOMContentLoaded', () => {
    if (typeof Chart === 'undefined') {
        const errEl = document.getElementById('report-chart-error');
        if (errEl) errEl.classList.remove('d-none');
        return;
    }

    const dataEl = document.getElementById('report-chart-data');
    if (!dataEl) return;

    const reportData = JSON.parse(dataEl.dataset.chartData);

    const catLabels = reportData.catLabels || [];
    const catRevenue = (reportData.catRevenue || []).map(Number);

    const revDateLabels = reportData.revDateLabels || [];
    const revDateData = (reportData.revDateData || []).map(Number);

    const revMonthLabels = reportData.revMonthLabels || [];
    const revMonthData = (reportData.revMonthData || []).map(Number);

    const revYearLabels = reportData.revYearLabels || [];
    const revYearData = (reportData.revYearData || []).map(Number);

    const payLabels = reportData.paymentMethodLabels || [];
    const payRevenue = (reportData.paymentMethodRevenue || []).map(Number);

    const mk = (el, type, labels, data, label, bgColor = 'rgba(54, 162, 235, 0.7)') => {
        if (!el) return null;
        return new Chart(el, {
            type,
            data: {
                labels,
                datasets: [{
                    label,
                    data,
                    backgroundColor: bgColor,
                    borderColor: bgColor.replace('0.7', '1'),
                    borderWidth: 1.5,
                    fill: type === 'line',
                    tension: 0.3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: (value) => new Intl.NumberFormat('vi-VN').format(value) + ' ₫'
                        }
                    }
                }
            }
        });
    };

    // 1. Danh mục
    mk(document.getElementById('categoryRevenueChart'), 'bar', catLabels, catRevenue, 'Doanh thu (VNĐ)', 'rgba(59, 130, 246, 0.7)');

    // 2. Theo ngày
    mk(document.getElementById('revenueByDateChart'), 'line', revDateLabels, revDateData, 'Doanh thu (VNĐ)', 'rgba(16, 185, 129, 0.7)');

    // 3. Theo tháng
    mk(document.getElementById('revenueByMonthChart'), 'bar', revMonthLabels, revMonthData, 'Doanh thu (VNĐ)', 'rgba(245, 158, 11, 0.7)');

    // 4. Theo năm
    mk(document.getElementById('revenueByYearChart'), 'bar', revYearLabels, revYearData, 'Doanh thu (VNĐ)', 'rgba(99, 102, 241, 0.7)');

    // 5. Theo phương thức thanh toán (Pie chart)
    const pieEl = document.getElementById('revenueByPaymentMethodChart');
    if (pieEl) {
        new Chart(pieEl, {
            type: 'pie',
            data: {
                labels: payLabels,
                datasets: [{
                    label: 'Doanh thu (VNĐ)',
                    data: payRevenue,
                    backgroundColor: [
                        '#a50064', // MoMo pink
                        '#10b981', // COD green
                        '#6b7280', // Other gray
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const val = context.parsed;
                                return ' ' + context.label + ': ' + new Intl.NumberFormat('vi-VN').format(val) + ' ₫';
                            }
                        }
                    }
                }
            }
        });
    }
});
</script>
@endsection
