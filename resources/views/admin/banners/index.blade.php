@extends('layouts.admin')

@section('title', 'Quản Lý Banner')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Banner Quảng Cáo</h4>
        <p class="text-muted small mb-0">Quản lý slider và banner khuyến mãi trên trang chủ</p>
    </div>
    <a href="{{ route('admin.banners.create') }}" class="btn btn-primary rounded-pill px-4 fw-bold">
        <i class="fa-solid fa-plus me-1"></i> Thêm Banner Mới
    </a>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="px-3" style="width: 70px;">ID</th>
                        <th>Hình ảnh</th>
                        <th>Tiêu đề</th>
                        <th>Đường dẫn liên kết</th>
                        <th>Vị trí</th>
                        <th>Trạng thái</th>
                        <th class="text-end px-3">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($banners as $banner)
                        <tr>
                            <td class="px-3 font-monospace text-muted">{{ $banner->id }}</td>
                            <td>
                                <img src="{{ asset($banner->image) }}" class="rounded-3 border" style="width: 120px; height: 50px; object-fit: cover;">
                            </td>
                            <td><strong>{{ $banner->title }}</strong></td>
                            <td class="small text-muted text-truncate" style="max-width: 200px;">{{ $banner->link ?? '-' }}</td>
                            <td><span class="badge bg-light text-dark border">{{ $banner->position }}</span></td>
                            <td>
                                <span class="badge {{ $banner->status === 'active' ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $banner->status === 'active' ? 'Hiển thị' : 'Tạm ẩn' }}
                                </span>
                            </td>
                            <td class="text-end px-3">
                                <a href="{{ route('admin.banners.edit', $banner->id) }}" class="btn btn-sm btn-outline-secondary btn-action me-1" title="Sửa">
                                    <i class="fa-solid fa-pen"></i>
                                </a>

                                <form action="{{ route('admin.banners.destroy', $banner->id) }}" method="POST" class="d-inline form-delete-confirm">
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
                            <td colspan="7" class="text-center py-4 text-muted">Chưa có banner nào.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
