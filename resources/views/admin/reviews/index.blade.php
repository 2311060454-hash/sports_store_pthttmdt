@extends('layouts.admin')

@section('title', 'Quản Lý Đánh Giá')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Kiểm Duyệt Đánh Giá Khách Hàng</h4>
        <p class="text-muted small mb-0">Quản lý nhận xét và xếp hạng sao từ khách hàng thực tế</p>
    </div>
</div>

<div class="card mb-4">
    <div class="card-body p-3">
        <form action="{{ route('admin.reviews.index') }}" method="GET" class="row g-2 align-items-center">
            <div class="col-md-3">
                <select name="rating" class="form-select form-select-sm">
                    <option value="">Tất cả số sao</option>
                    <option value="5" {{ request('rating') == 5 ? 'selected' : '' }}>5 sao</option>
                    <option value="4" {{ request('rating') == 4 ? 'selected' : '' }}>4 sao</option>
                    <option value="3" {{ request('rating') == 3 ? 'selected' : '' }}>3 sao</option>
                    <option value="2" {{ request('rating') == 2 ? 'selected' : '' }}>2 sao</option>
                    <option value="1" {{ request('rating') == 1 ? 'selected' : '' }}>1 sao</option>
                </select>
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select form-select-sm">
                    <option value="">Tất cả trạng thái</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Hiển thị</option>
                    <option value="hidden" {{ request('status') === 'hidden' ? 'selected' : '' }}>Đang ẩn</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-sm btn-primary w-100 rounded-pill">Lọc</button>
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
                        <th class="px-3">Sản phẩm</th>
                        <th>Khách hàng</th>
                        <th>Đơn hàng</th>
                        <th>Điểm</th>
                        <th>Nội dung nhận xét</th>
                        <th>Thời gian</th>
                        <th>Trạng thái</th>
                        <th class="text-end px-3">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reviews as $rev)
                        <tr>
                            <td class="px-3">
                                <div class="fw-semibold text-truncate" style="max-width: 180px;" title="{{ $rev->product->name ?? '' }}">
                                    {{ $rev->product->name ?? '-' }}
                                </div>
                            </td>
                            <td>
                                <div class="fw-semibold">{{ $rev->user->name ?? '-' }}</div>
                            </td>
                            <td class="font-monospace small">#{{ $rev->order->order_code ?? '-' }}</td>
                            <td>
                                <div class="text-warning text-nowrap">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fa-{{ $i <= $rev->rating ? 'solid' : 'regular' }} fa-star"></i>
                                    @endfor
                                </div>
                            </td>
                            <td>
                                <p class="mb-0 small text-secondary" style="max-width: 250px;">{{ $rev->comment }}</p>
                            </td>
                            <td class="small text-muted">{{ $rev->created_at->format('d/m/Y') }}</td>
                            <td>
                                <span class="badge {{ $rev->status === 'active' ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $rev->status === 'active' ? 'Hiển thị' : 'Đang ẩn' }}
                                </span>
                            </td>
                            <td class="text-end px-3">
                                <form action="{{ route('admin.reviews.toggle', $rev->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-sm btn-outline-{{ $rev->status === 'active' ? 'warning' : 'success' }} btn-action me-1" title="{{ $rev->status === 'active' ? 'Ẩn đánh giá' : 'Hiển thị đánh giá' }}">
                                        <i class="fa-solid {{ $rev->status === 'active' ? 'fa-eye-slash' : 'fa-eye' }}"></i>
                                    </button>
                                </form>

                                <form action="{{ route('admin.reviews.destroy', $rev->id) }}" method="POST" class="d-inline form-delete-confirm">
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
                            <td colspan="8" class="text-center py-4 text-muted">Chưa có đánh giá nào.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($reviews->hasPages())
        <div class="card-footer bg-white py-3 d-flex justify-content-center">
            {{ $reviews->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>
@endsection
