@extends('layouts.admin')

@section('title', 'Quản Lý Đơn Hàng')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
    <div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-1 small">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none text-muted">Quản trị</a></li>
                <li class="breadcrumb-item active text-primary fw-semibold" aria-current="page">Đơn hàng</li>
            </ol>
        </nav>
        <h4 class="fw-bold mb-0">Quản Lý Đơn Hàng</h4>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.finance.index') }}" class="btn btn-sm btn-outline-info rounded-pill px-3">
            <i class="fa-solid fa-chart-pie me-1"></i> Thống kê tài chính
        </a>
        <a href="{{ route('admin.ghn.index') }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
            <i class="fa-solid fa-truck-fast me-1"></i> Đồng bộ GHN
        </a>
    </div>
</div>

<!-- Tabs Bar (Theo đúng chuẩn Lab Admin) -->
<div class="card border-0 shadow-sm rounded-4 mb-3 overflow-hidden">
    <div class="card-body p-2 bg-white">
        <div class="d-flex flex-wrap gap-1">
            @foreach($tabs as $tabKey => $tab)
                @php
                    $isActive = ($activeTab === $tabKey);
                    $colorClass = match($tab['color']) {
                        'blue' => 'primary',
                        'slate' => 'secondary',
                        'cyan' => 'info',
                        'amber' => 'warning',
                        'green' => 'success',
                        'orange' => 'danger',
                        'red' => 'danger',
                        default => 'secondary',
                    };
                @endphp
                <a href="{{ request()->fullUrlWithQuery(['tab' => $tabKey, 'page' => 1]) }}" 
                   class="btn btn-sm rounded-pill px-3 py-1 fw-semibold d-flex align-items-center gap-2 {{ $isActive ? 'btn-' . $colorClass . ' text-white shadow-sm' : 'btn-light text-dark' }}">
                    <span>{{ $tab['label'] }}</span>
                    <span class="badge rounded-pill {{ $isActive ? 'bg-white text-dark' : 'bg-' . $colorClass . ' text-white' }} px-2">
                        {{ $tab['count'] }}
                    </span>
                </a>
            @endforeach
        </div>
    </div>
</div>

<!-- Bộ Lọc Nâng Cao (Filters) -->
<div class="card border-0 shadow-sm rounded-4 mb-3">
    <div class="card-body p-3">
        <form action="{{ route('admin.orders.index') }}" method="GET" class="row g-2 align-items-center">
            <input type="hidden" name="tab" value="{{ $activeTab }}">

            <div class="col-lg-3 col-md-6">
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                    <input type="text" name="search" class="form-control border-start-0" placeholder="Mã đơn, khách hàng, SĐT, SP..." value="{{ $filters['search'] ?? request('search') }}">
                </div>
            </div>

            <div class="col-lg-2 col-md-3 col-6">
                <select name="gateway" class="form-select form-select-sm">
                    <option value="">-- Cổng TT --</option>
                    <option value="cod" {{ ($filters['gateway'] ?? '') === 'cod' ? 'selected' : '' }}>COD (Tiền mặt)</option>
                    <option value="momo" {{ ($filters['gateway'] ?? '') === 'momo' ? 'selected' : '' }}>Ví MoMo</option>
                    <option value="bank_transfer" {{ ($filters['gateway'] ?? '') === 'bank_transfer' ? 'selected' : '' }}>Chuyển khoản</option>
                </select>
            </div>

            <div class="col-lg-2 col-md-3 col-6">
                <select name="payment_status" class="form-select form-select-sm">
                    <option value="">-- Trạng thái TT --</option>
                    @foreach($paymentLabels as $val => $lbl)
                        <option value="{{ $val }}" {{ ($filters['payment_status'] ?? '') === $val ? 'selected' : '' }}>{{ $lbl }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-lg-2 col-md-3 col-6">
                <input type="date" name="date_from" class="form-control form-control-sm" value="{{ $filters['date_from'] ?? '' }}" title="Từ ngày">
            </div>

            <div class="col-lg-2 col-md-3 col-6">
                <input type="date" name="date_to" class="form-control form-control-sm" value="{{ $filters['date_to'] ?? '' }}" title="Đến ngày">
            </div>

            <div class="col-lg-1 col-md-12 d-flex gap-1">
                <button type="submit" class="btn btn-sm btn-primary w-100 rounded-pill"><i class="fa-solid fa-filter me-1"></i> Lọc</button>
                <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill" title="Xóa lọc"><i class="fa-solid fa-rotate-left"></i></a>
            </div>
        </form>
    </div>
</div>

<!-- Danh Sách Đơn Hàng (Bảng theo đúng mẫu Screenshot PDF) -->
<div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
    <div class="card-header bg-white py-3 px-4 d-flex justify-content-between align-items-center">
        <span class="fw-bold text-dark">
            <i class="fa-solid fa-list-check me-2 text-primary"></i> Danh sách đơn hàng ({{ $orders->total() }} đơn)
        </span>
        <div class="small text-muted">
            Trạng thái vận chuyển được đồng bộ theo thời gian thực từ GHN
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0" style="font-size: 0.875rem;">
            <thead class="table-light">
                <tr>
                    <th class="py-3 px-3">Mã đơn hàng</th>
                    <th>Ngày tạo đơn</th>
                    <th>Sản phẩm</th>
                    <th>Tổng tiền</th>
                    <th>COD cần thu</th>
                    <th>Tên khách hàng</th>
                    <th>Mã vận đơn</th>
                    <th>Trạng thái giao hàng</th>
                    <th>Đơn vị VC</th>
                    <th class="text-end px-3">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                    @php
                        $paymentBadge = match($order->payment_status) {
                            'paid' => 'bg-success',
                            'failed' => 'bg-danger',
                            'initiated' => 'bg-info text-dark',
                            default => 'bg-secondary',
                        };
                        $isMomo = $order->gateway === 'momo' || $order->payment_method === 'momo';
                    @endphp
                    <tr>
                        <td class="px-3">
                            <a href="{{ route('admin.orders.show', $order->id) }}" class="fw-bold font-monospace text-decoration-none text-primary">
                                #{{ $order->order_code }}
                            </a>
                            <div class="mt-1">
                                <span class="badge {{ $paymentBadge }} px-2 py-0" style="font-size: 0.7rem;">
                                    {{ $paymentLabels[$order->payment_status] ?? $order->payment_status }}
                                </span>
                                @if($isMomo)
                                    <span class="badge text-white px-2 py-0" style="background-color: #a50064; font-size: 0.7rem;">MoMo</span>
                                @else
                                    <span class="badge bg-light text-dark border px-2 py-0" style="font-size: 0.7rem;">COD</span>
                                @endif
                            </div>
                        </td>
                        <td class="small text-muted text-nowrap">
                            <div>{{ $order->created_at->format('d/m/Y') }}</div>
                            <div style="font-size: 0.75rem;">{{ $order->created_at->format('H:i') }}</div>
                        </td>
                        <td>
                            @php
                                $firstItem = $order->items->first();
                                $otherCount = $order->items->count() - 1;
                            @endphp
                            @if($firstItem)
                                <div class="fw-semibold text-truncate" style="max-width: 170px;" title="{{ $firstItem->product_name }}">
                                    {{ $firstItem->product_name }}
                                </div>
                                <small class="text-muted">SL: {{ $firstItem->quantity }} @if($otherCount > 0) <span class="badge bg-light text-dark border">+{{ $otherCount }} món khác</span> @endif</small>
                            @else
                                <span class="text-muted small">Không có sản phẩm</span>
                            @endif
                        </td>
                        <td>
                            <strong class="text-dark">{{ number_format($order->total_amount, 0, ',', '.') }} ₫</strong>
                        </td>
                        <td>
                            @php
                                $codAmount = ($order->payment_status === 'paid' || $isMomo) ? 0 : $order->total_amount;
                            @endphp
                            @if($codAmount > 0)
                                <strong class="text-danger">{{ number_format($codAmount, 0, ',', '.') }} ₫</strong>
                            @else
                                <span class="badge bg-success-subtle text-success border border-success-subtle">0 ₫ (Đã TT)</span>
                            @endif
                        </td>
                        <td>
                            <div class="fw-semibold">{{ $order->customer_name }}</div>
                            <small class="text-muted font-monospace"><i class="fa-solid fa-phone me-1" style="font-size: 0.7rem;"></i>{{ $order->customer_phone }}</small>
                        </td>
                        <td>
                            @if($order->ghn_order_code)
                                <a href="https://5sao.ghn.dev/tracking" target="_blank" class="badge bg-primary-subtle text-primary border border-primary-subtle text-decoration-none font-monospace">
                                    {{ $order->ghn_order_code }} <i class="fa-solid fa-arrow-up-right-from-square ms-1" style="font-size: 0.65rem;"></i>
                                </a>
                            @else
                                <span class="text-muted small">Chưa có vận đơn</span>
                            @endif
                        </td>
                        <td>
                            @php
                                $shippingBadge = match($order->shipping_status) {
                                    'delivered' => 'bg-success',
                                    'delivering', 'picked', 'storing', 'transporting', 'sorting' => 'bg-primary',
                                    'ready_to_pick', 'picking' => 'bg-info text-dark',
                                    'cancelled', 'return', 'returned' => 'bg-danger',
                                    default => 'bg-secondary',
                                };
                            @endphp
                            <span class="badge {{ $shippingBadge }} px-2 py-1">
                                <i class="fa-solid fa-circle me-1" style="font-size: 0.5rem;"></i>
                                {{ $shippingLabels[$order->shipping_status] ?? $order->shipping_status }}
                            </span>
                        </td>
                        <td>
                            @if($order->ghn_order_code)
                                <span class="badge bg-warning-subtle text-dark border border-warning-subtle fw-bold">GHN</span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td class="text-end px-3 text-nowrap">
                            <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-outline-primary rounded-pill px-2 me-1" title="Xem chi tiết">
                                <i class="fa-solid fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.orders.invoice', $order->id) }}" target="_blank" class="btn btn-sm btn-outline-secondary rounded-pill px-2 me-1" title="In hóa đơn">
                                <i class="fa-solid fa-print"></i>
                            </a>

                            @php
                                $isDelivering = in_array($order->shipping_status, ['delivering', 'picked', 'storing', 'transporting', 'sorting'], true) || $order->status === 'shipping';
                            @endphp

                            @if(!$isDelivering && $order->status !== 'cancelled' && $order->status !== 'completed')
                                <button type="button" class="btn btn-sm btn-outline-danger rounded-pill px-2" data-bs-toggle="modal" data-bs-target="#cancelModal{{ $order->id }}" title="Hủy đơn hàng">
                                    <i class="fa-solid fa-ban"></i>
                                </button>

                                <!-- Modal Hủy đơn -->
                                <div class="modal fade" id="cancelModal{{ $order->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content border-0 shadow rounded-4 text-start">
                                            <form action="{{ route('admin.orders.cancel', $order->id) }}" method="POST">
                                                @csrf
                                                <div class="modal-header border-0 pb-0">
                                                    <h5 class="modal-title fw-bold text-danger"><i class="fa-solid fa-triangle-exclamation me-2"></i> Xác nhận hủy đơn hàng</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body py-3">
                                                    <p>Bạn có chắc chắn muốn hủy đơn hàng <strong>#{{ $order->order_code }}</strong> của khách hàng <strong>{{ $order->customer_name }}</strong>?</p>
                                                    <div class="mb-3">
                                                        <label class="form-label small fw-semibold">Lý do hủy:</label>
                                                        <textarea name="cancel_reason" class="form-control" rows="2" placeholder="Ví dụ: Khách gọi yêu cầu hủy, hết hàng..." required></textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer border-0 pt-0">
                                                    <button type="button" class="btn btn-light rounded-pill px-3" data-bs-dismiss="modal">Đóng</button>
                                                    <button type="submit" class="btn btn-danger rounded-pill px-4">Xác nhận hủy đơn</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @elseif($isDelivering)
                                <button class="btn btn-sm btn-light text-muted rounded-pill px-2" disabled title="Đơn đang giao không thể hủy">
                                    <i class="fa-solid fa-lock"></i>
                                </button>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="text-center py-5 text-muted">
                            <i class="fa-solid fa-box-open fs-2 mb-2 text-secondary"></i>
                            <div>Không tìm thấy đơn hàng nào phù hợp với bộ lọc.</div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($orders->hasPages())
        <div class="card-footer bg-white border-0 py-3 d-flex justify-content-between align-items-center">
            <span class="small text-muted">Hiển thị {{ $orders->firstItem() }} - {{ $orders->lastItem() }} trên tổng {{ $orders->total() }} đơn</span>
            {{ $orders->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>
@endsection
