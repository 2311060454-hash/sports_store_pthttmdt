@extends('layouts.admin')

@section('title', 'Quản Lý Liên Hệ & Phản Hồi - Sport Store Admin')

@section('admin_content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Hộp Thư Liên Hệ & Phản Hồi</h3>
        <p class="text-muted small mb-0">Quản lý tin nhắn, thắc mắc và đóng góp ý kiến từ khách hàng</p>
    </div>
</div>

<!-- Filter card -->
<div class="card border-0 shadow-sm rounded-4 p-3 mb-4">
    <form action="{{ route('admin.contacts.index') }}" method="GET" class="row g-3 align-items-center">
        <div class="col-md-5">
            <div class="input-group">
                <span class="input-group-text bg-white border-end-0"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                <input type="text" name="q" value="{{ request('q') }}" class="form-control border-start-0" placeholder="Tìm theo tên, email, SĐT hoặc tiêu đề...">
            </div>
        </div>
        <div class="col-md-4">
            <select name="status" class="form-select" onchange="this.form.submit()">
                <option value="">-- Tất cả trạng thái --</option>
                <option value="unread" {{ request('status') === 'unread' ? 'selected' : '' }}>Chưa đọc</option>
                <option value="read" {{ request('status') === 'read' ? 'selected' : '' }}>Đã xem</option>
                <option value="replied" {{ request('status') === 'replied' ? 'selected' : '' }}>Đã phản hồi</option>
            </select>
        </div>
        <div class="col-md-3 d-flex gap-2">
            <button type="submit" class="btn btn-primary rounded-3 flex-grow-1">Lọc</button>
            <a href="{{ route('admin.contacts.index') }}" class="btn btn-outline-secondary rounded-3">Đặt lại</a>
        </div>
    </form>
</div>

<!-- Table -->
<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th style="width: 60px;" class="text-center">STT</th>
                    <th>Khách hàng</th>
                    <th>Chủ đề</th>
                    <th>Nội dung</th>
                    <th class="text-center">Trạng thái</th>
                    <th>Thời gian gửi</th>
                    <th class="text-end" style="width: 140px;">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($contacts as $index => $contact)
                    <tr class="{{ $contact->status === 'unread' ? 'fw-bold table-light' : '' }}">
                        <td class="text-center">{{ $contacts->firstItem() + $index }}</td>
                        <td>
                            <div>{{ $contact->name }}</div>
                            <small class="text-muted"><i class="fa-solid fa-envelope me-1"></i> {{ $contact->email }}</small>
                            @if($contact->phone)
                                <div class="small text-muted"><i class="fa-solid fa-phone me-1"></i> {{ $contact->phone }}</div>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-secondary-subtle text-secondary">{{ $contact->subject ?: 'Chung' }}</span>
                        </td>
                        <td>
                            <div class="text-truncate" style="max-width: 250px;">{{ $contact->message }}</div>
                        </td>
                        <td class="text-center">
                            {!! $contact->status_badge !!}
                        </td>
                        <td class="small text-muted">
                            {{ $contact->created_at->format('d/m/Y H:i') }}
                        </td>
                        <td class="text-end">
                            <div class="d-flex justify-content-end gap-1">
                                <a href="{{ route('admin.contacts.show', $contact->id) }}" class="btn btn-sm btn-outline-primary" title="Xem chi tiết">
                                    <i class="fa-solid fa-eye"></i>
                                </a>
                                <form action="{{ route('admin.contacts.destroy', $contact->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc muốn xóa tin nhắn liên hệ này?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Xóa">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">Không có tin nhắn liên hệ nào.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($contacts->hasPages())
        <div class="p-3 border-top">
            {{ $contacts->links() }}
        </div>
    @endif
</div>
@endsection
