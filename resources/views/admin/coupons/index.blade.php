@extends('layouts.admin')

@section('title', 'Quản Lý Mã Giảm Giá')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Mã Giảm Giá & Voucher</h4>
        <p class="text-muted small mb-0">Quản lý các chương trình chiết khấu, khuyến mãi thể thao</p>
    </div>
    <a href="{{ route('admin.coupons.create') }}" class="btn btn-primary rounded-pill px-4 fw-bold">
        <i class="fa-solid fa-plus me-1"></i> Tạo Mã Mới
    </a>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="px-3">Mã Code</th>
                        <th>Loại giảm</th>
                        <th>Giá trị</th>
                        <th>Đơn tối thiểu</th>
                        <th>Thời hạn</th>
                        <th class="text-center">Đã dùng / Giới hạn</th>
                        <th>Trạng thái</th>
                        <th class="text-end px-3">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($coupons as $coupon)
                        <tr>
                            <td class="px-3">
                                <span class="badge bg-primary-subtle text-primary fs-6 font-monospace px-3 py-2">
                                    {{ $coupon->code }}
                                </span>
                            </td>
                            <td>
                                <span class="badge {{ $coupon->type === 'percent' ? 'bg-info text-dark' : 'bg-success' }}">
                                    {{ $coupon->type === 'percent' ? 'Phần trăm (%)' : 'Số tiền cố định' }}
                                </span>
                            </td>
                            <td class="fw-bold text-dark">
                                @if($coupon->type === 'percent')
                                    {{ $coupon->value }}%
                                    @if($coupon->max_discount)
                                        <small class="text-muted d-block">(Tối đa {{ number_format($coupon->max_discount, 0, ',', '.') }}₫)</small>
                                    @endif
                                @else
                                    {{ number_format($coupon->value, 0, ',', '.') }} ₫
                                @endif
                            </td>
                            <td class="small">{{ number_format($coupon->min_order_amount, 0, ',', '.') }} ₫</td>
                            <td class="small">
                                <div>Bắt đầu: {{ $coupon->start_date ? $coupon->start_date->format('d/m/Y') : 'Không giới hạn' }}</div>
                                <div>Kết thúc: {{ $coupon->end_date ? $coupon->end_date->format('d/m/Y') : 'Không giới hạn' }}</div>
                            </td>
                            <td class="text-center font-monospace">
                                <strong>{{ $coupon->used }}</strong> / {{ $coupon->quantity }}
                            </td>
                            <td>
                                <span class="badge {{ $coupon->status === 'active' ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $coupon->status === 'active' ? 'Hoạt động' : 'Tắt' }}
                                </span>
                            </td>
                            <td class="text-end px-3">
                                <a href="{{ route('admin.coupons.edit', $coupon->id) }}" class="btn btn-sm btn-outline-secondary btn-action me-1" title="Sửa">
                                    <i class="fa-solid fa-pen"></i>
                                </a>

                                <form action="{{ route('admin.coupons.destroy', $coupon->id) }}" method="POST" class="d-inline form-delete-confirm">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger btn-action" title="Xóa">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">Chưa có mã giảm giá nào.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($coupons->hasPages())
        <div class="card-footer bg-white py-3 d-flex justify-content-center">
            {{ $coupons->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>
@endsection
