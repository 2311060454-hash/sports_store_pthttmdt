@extends('layouts.admin')

@section('title', 'Chi Tiết Liên Hệ #' . $contact->id . ' - Sport Store Admin')

@section('admin_content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Chi Tiết Liên Hệ #{{ $contact->id }}</h3>
        <p class="text-muted small mb-0">Xem và cập nhật trạng thái xử lý phản hồi của khách hàng</p>
    </div>
    <a href="{{ route('admin.contacts.index') }}" class="btn btn-outline-secondary rounded-pill px-3">
        <i class="fa-solid fa-arrow-left me-1"></i> Quay lại
    </a>
</div>

<div class="row g-4">
    <!-- Left: Message details -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
            <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-3">
                <h5 class="fw-bold mb-0">Nội Dung Tin Nhắn</h5>
                <div>{!! $contact->status_badge !!}</div>
            </div>

            <div class="mb-3">
                <span class="text-muted small">Tiêu đề / Chủ đề:</span>
                <div class="fw-bold fs-5 text-dark">{{ $contact->subject ?: 'Không có chủ đề' }}</div>
            </div>

            <div class="mb-3">
                <span class="text-muted small">Thời gian gửi:</span>
                <div>{{ $contact->created_at->format('d/m/Y H:i:s') }} ({{ $contact->created_at->diffForHumans() }})</div>
            </div>

            <div class="mb-4">
                <span class="text-muted small">Chi tiết nội dung:</span>
                <div class="p-3 bg-light rounded-3 mt-1 leading-relaxed border" style="white-space: pre-line;">{{ $contact->message }}</div>
            </div>

            <!-- Direct action: Email Reply -->
            <div class="d-flex gap-2">
                <a href="mailto:{{ $contact->email }}?subject=Phản hồi từ Sport Store: {{ urlencode($contact->subject) }}" class="btn btn-primary rounded-pill px-4">
                    <i class="fa-solid fa-reply me-1"></i> Gửi Email Phản Hồi Trực Tiếp
                </a>
                @if($contact->phone)
                    <a href="tel:{{ $contact->phone }}" class="btn btn-outline-success rounded-pill px-4">
                        <i class="fa-solid fa-phone me-1"></i> Gọi Điện Thoại ({{ $contact->phone }})
                    </a>
                @endif
            </div>
        </div>
    </div>

    <!-- Right: Customer Info & Status Update -->
    <div class="col-lg-4">
        <!-- Customer Info -->
        <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
            <h6 class="fw-bold mb-3 border-bottom pb-2">Thông Tin Người Gửi</h6>
            <div class="mb-2">
                <div class="small text-muted">Họ và tên:</div>
                <div class="fw-bold">{{ $contact->name }}</div>
            </div>
            <div class="mb-2">
                <div class="small text-muted">Địa chỉ Email:</div>
                <div class="fw-semibold text-primary">{{ $contact->email }}</div>
            </div>
            <div class="mb-2">
                <div class="small text-muted">Số điện thoại:</div>
                <div class="fw-semibold">{{ $contact->phone ?: 'Chưa cung cấp' }}</div>
            </div>
        </div>

        <!-- Status update form -->
        <div class="card border-0 shadow-sm rounded-4 p-4">
            <h6 class="fw-bold mb-3 border-bottom pb-2">Cập Nhật Trạng Thái</h6>
            <form action="{{ route('admin.contacts.update', $contact->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label fw-semibold">Trạng thái xử lý:</label>
                    <select name="status" class="form-select">
                        <option value="unread" {{ $contact->status === 'unread' ? 'selected' : '' }}>Chưa đọc</option>
                        <option value="read" {{ $contact->status === 'read' ? 'selected' : '' }}>Đã xem</option>
                        <option value="replied" {{ $contact->status === 'replied' ? 'selected' : '' }}>Đã phản hồi khách</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Ghi chú nội bộ của Admin:</label>
                    <textarea name="admin_notes" class="form-control" rows="3" placeholder="Ghi chú nội dung đã hỗ trợ...">{{ old('admin_notes', $contact->admin_notes) }}</textarea>
                </div>

                <button type="submit" class="btn btn-primary w-100 rounded-3">
                    <i class="fa-solid fa-floppy-disk me-1"></i> Lưu Cập Nhật
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
