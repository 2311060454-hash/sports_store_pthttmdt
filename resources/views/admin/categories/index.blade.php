@extends('layouts.admin')

@section('title', 'Quản Lý Danh Mục')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Danh Mục Sản Phẩm</h4>
        <p class="text-muted small mb-0">Quản lý các môn và phân loại dụng cụ thể thao</p>
    </div>
    <a href="{{ route('admin.categories.create') }}" class="btn btn-primary rounded-pill px-4 fw-bold">
        <i class="fa-solid fa-plus me-1"></i> Thêm Danh Mục Mới
    </a>
</div>

<div class="card">
    <div class="card-header bg-white py-3">
        <form action="{{ route('admin.categories.index') }}" method="GET" class="row g-2">
            <div class="col-md-4">
                <div class="input-group input-group-sm">
                    <input type="text" name="q" class="form-control" placeholder="Tìm tên danh mục..." value="{{ request('q') }}">
                    <button class="btn btn-outline-secondary" type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
                </div>
            </div>
            @if(request('q'))
                <div class="col-auto">
                    <a href="{{ route('admin.categories.index') }}" class="btn btn-sm btn-outline-danger">Xóa tìm</a>
                </div>
            @endif
        </form>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="px-3" style="width: 70px;">ID</th>
                        <th>Hình ảnh</th>
                        <th>Tên danh mục</th>
                        <th>Slug</th>
                        <th class="text-center">Số sản phẩm</th>
                        <th>Trạng thái</th>
                        <th class="text-end px-3">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $category)
                        <tr>
                            <td class="px-3 font-monospace text-muted">{{ $category->id }}</td>
                            <td>
                                <img src="{{ asset($category->image ?? 'https://images.unsplash.com/photo-1546519638-68e109498ffc?w=100') }}" 
                                     alt="{{ $category->name }}" 
                                     class="rounded-3 border" 
                                     style="width: 48px; height: 48px; object-fit: cover;">
                            </td>
                            <td><strong>{{ $category->name }}</strong></td>
                            <td class="small text-muted font-monospace">{{ $category->slug }}</td>
                            <td class="text-center">
                                <span class="badge bg-light text-dark border">{{ $category->products_count }}</span>
                            </td>
                            <td>
                                <span class="badge {{ $category->status === 'active' ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $category->status === 'active' ? 'Hoạt động' : 'Tạm ẩn' }}
                                </span>
                            </td>
                            <td class="text-end px-3">
                                <a href="{{ route('admin.categories.edit', $category->id) }}" class="btn btn-sm btn-outline-secondary btn-action me-1" title="Sửa">
                                    <i class="fa-solid fa-pen"></i>
                                </a>

                                <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" class="d-inline form-delete-confirm">
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
                            <td colspan="7" class="text-center py-4 text-muted">Chưa có danh mục nào được tạo.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($categories->hasPages())
        <div class="card-footer bg-white py-3 d-flex justify-content-center">
            {{ $categories->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>
@endsection
