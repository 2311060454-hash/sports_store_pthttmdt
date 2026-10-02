@extends('layouts.admin')

@section('title', 'Quản Lý Người Dùng')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-1 small">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none text-muted">Quản trị</a></li>
                <li class="breadcrumb-item active text-primary fw-semibold" aria-current="page">Người dùng</li>
            </ol>
        </nav>
        <h4 class="fw-bold mb-0">Quản Lý Người Dùng</h4>
    </div>
    <a href="{{ route('admin.users.create') }}" class="btn btn-primary rounded-pill px-4 shadow-sm">
        <i class="fa-solid fa-plus me-1"></i> Thêm người dùng mới
    </a>
</div>

<!-- Search & Filter Card -->
<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-body p-3">
        <form action="{{ route('admin.users.index') }}" method="GET" class="row g-2 align-items-center">
            <div class="col-md-8">
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                    <input type="text" name="q" class="form-control border-start-0" placeholder="Tìm theo tên, email, số điện thoại..." value="{{ request('q') }}">
                </div>
            </div>
            <div class="col-md-3">
                <select name="role" class="form-select form-select-sm">
                    <option value="">Tất cả vai trò</option>
                    <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Quản trị viên (Admin)</option>
                    <option value="user" {{ request('role') === 'user' ? 'selected' : '' }}>Khách hàng (User)</option>
                </select>
            </div>
            <div class="col-md-1 d-flex gap-1">
                <button type="submit" class="btn btn-sm btn-primary w-100 rounded-pill">Lọc</button>
            </div>
        </form>
    </div>
</div>

<!-- Table Card -->
<div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th class="py-3 px-4" style="width: 70px;">ID</th>
                    <th>Tên người dùng</th>
                    <th>Email</th>
                    <th>Vai trò</th>
                    <th>Ngày tạo</th>
                    <th class="text-end px-4">Hành động</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr>
                        <td class="px-4 font-monospace text-muted">#{{ $user->id }}</td>
                        <td>
                            <div class="fw-bold text-dark">{{ $user->name }}</div>
                            @if($user->phone)
                                <small class="text-muted"><i class="fa-solid fa-phone me-1" style="font-size: 0.7rem;"></i>{{ $user->phone }}</small>
                            @endif
                        </td>
                        <td>
                            <span class="text-dark">{{ $user->email }}</span>
                            @if($user->email_verified_at)
                                <span class="badge bg-success-subtle text-success ms-1 small" title="Đã xác thực email"><i class="fa-solid fa-circle-check"></i></span>
                            @endif
                        </td>
                        <td>
                            @if($user->isAdmin())
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-3 py-1 fw-bold">
                                    <i class="fa-solid fa-shield-halved me-1"></i> Quản trị (Admin)
                                </span>
                            @else
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-1">
                                    <i class="fa-solid fa-user me-1"></i> Khách hàng (User)
                                </span>
                            @endif
                        </td>
                        <td class="small text-muted">{{ $user->created_at->format('d/m/Y') }}</td>
                        <td class="text-end px-4 text-nowrap">
                            <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-sm btn-outline-info rounded-pill px-3 me-1">
                                <i class="fa-solid fa-eye me-1"></i> Xem
                            </a>
                            <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3 me-1">
                                <i class="fa-solid fa-pen-to-square me-1"></i> Sửa
                            </a>
                            @if($user->id !== auth()->id())
                                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa người dùng {{ $user->name }}?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-3">
                                        <i class="fa-solid fa-trash me-1"></i> Xóa
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">Không có người dùng nào.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($users->hasPages())
        <div class="card-footer bg-white border-0 py-3 d-flex justify-content-between align-items-center">
            <span class="small text-muted">Hiển thị {{ $users->firstItem() }} - {{ $users->lastItem() }} trên tổng số {{ $users->total() }} người dùng</span>
            {{ $users->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>
@endsection
