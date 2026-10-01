@extends('layouts.app')

@section('title', 'Liên Hệ Với Chúng Tôi - Sport Store')

@section('content')
<!-- Page Header -->
<div class="bg-dark text-white py-5 mb-5" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);">
    <div class="container text-center py-4">
        <span class="badge bg-warning text-dark px-3 py-2 fw-bold text-uppercase mb-3">Hỗ Trợ 24/7</span>
        <h1 class="display-4 fw-extrabold mb-3">Liên Hệ Với Sport Store</h1>
        <p class="lead text-light opacity-75 max-w-700 mx-auto" style="max-width: 700px;">
            Chúng tôi luôn lắng nghe và sẵn sàng giải đáp mọi thắc mắc của bạn về sản phẩm, đơn hàng và chính sách bảo hành.
        </p>
    </div>
</div>

<div class="container py-3 mb-5">
    <div class="row g-5">
        <!-- Contact Form (Left) -->
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5">
                <h3 class="fw-bold mb-2">Gửi Tin Nhắn Cho Chúng Tôi</h3>
                <p class="text-muted small mb-4">Điền thông tin vào mẫu bên dưới, nhân viên hỗ trợ sẽ liên hệ lại với bạn trong vòng 24 giờ làm việc.</p>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show rounded-3 mb-4" role="alert">
                        <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <form action="{{ route('contact.submit') }}" method="POST">
                    @csrf

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Họ và tên <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', auth()->user()->name ?? '') }}" placeholder="Nguyễn Văn A" required>
                            @error('name')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Số điện thoại</label>
                            <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', auth()->user()->phone ?? '') }}" placeholder="0901234567">
                            @error('phone')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Địa chỉ Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', auth()->user()->email ?? '') }}" placeholder="email@example.com" required>
                            @error('email')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Chủ đề thắc mắc</label>
                            <select name="subject" class="form-select">
                                <option value="Tư vấn chọn sản phẩm / size">Tư vấn chọn sản phẩm / size</option>
                                <option value="Hỏi về tình trạng đơn hàng">Hỏi về tình trạng đơn hàng</option>
                                <option value="Bảo hành & đổi trả sản phẩm">Bảo hành & đổi trả sản phẩm</option>
                                <option value="Hợp tác đại lý & doanh nghiệp">Hợp tác đại lý & doanh nghiệp</option>
                                <option value="Ý kiến đóng góp khác">Ý kiến đóng góp khác</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Nội dung tin nhắn <span class="text-danger">*</span></label>
                        <textarea name="message" class="form-control @error('message') is-invalid @enderror" rows="5" placeholder="Mô tả chi tiết câu hỏi hoặc yêu cầu của bạn..." required>{{ old('message') }}</textarea>
                        @error('message')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary rounded-pill px-4 py-2 fw-bold">
                        <i class="fa-solid fa-paper-plane me-2"></i> Gửi Tin Nhắn Ngay
                    </button>
                </form>
            </div>
        </div>

        <!-- Contact Info & Showrooms (Right) -->
        <div class="col-lg-5">
            <!-- Fast Info Card -->
            <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
                <h4 class="fw-bold mb-3">Thông Tin Trực Tiếp</h4>
                
                <div class="d-flex align-items-start mb-3">
                    <div class="rounded-circle bg-primary-subtle text-primary p-3 me-3" style="width: 48px; height: 48px; display: flex; align-items: center; justify-content: center;">
                        <i class="fa-solid fa-phone fs-5"></i>
                    </div>
                    <div>
                        <div class="fw-bold">Tổng Đài Hotline Miễn Phí</div>
                        <div class="text-primary fw-extrabold fs-5">1900 8888</div>
                        <small class="text-muted">Hoạt động từ 08:00 - 22:00 hằng ngày</small>
                    </div>
                </div>

                <div class="d-flex align-items-start mb-3">
                    <div class="rounded-circle bg-success-subtle text-success p-3 me-3" style="width: 48px; height: 48px; display: flex; align-items: center; justify-content: center;">
                        <i class="fa-solid fa-envelope fs-5"></i>
                    </div>
                    <div>
                        <div class="fw-bold">Hòm Thư Điện Tử</div>
                        <div class="fw-semibold">support@sportstore.com</div>
                        <small class="text-muted">Phản hồi thư trong vòng 12 tiếng</small>
                    </div>
                </div>

                <div class="d-flex align-items-start">
                    <div class="rounded-circle bg-warning-subtle text-warning p-3 me-3" style="width: 48px; height: 48px; display: flex; align-items: center; justify-content: center;">
                        <i class="fa-solid fa-clock fs-5"></i>
                    </div>
                    <div>
                        <div class="fw-bold">Thời Gian Làm Việc</div>
                        <div class="fw-semibold">Thứ 2 - Chủ Nhật (Kể cả ngày lễ)</div>
                        <small class="text-muted">Showroom mở cửa 08:30 - 21:30</small>
                    </div>
                </div>
            </div>

            <!-- Showrooms list -->
            <div class="card border-0 shadow-sm rounded-4 p-4">
                <h5 class="fw-bold mb-3"><i class="fa-solid fa-store me-2 text-primary"></i> Hệ Thống Showroom</h5>
                <ul class="list-unstyled mb-0 d-flex flex-column gap-3 small">
                    <li class="border-bottom pb-2">
                        <strong class="text-dark">Hà Nội (Trụ sở chính):</strong><br>
                        <span class="text-muted">123 Đường Cầu Giấy, P. Dịch Vọng, Q. Cầu Giấy, Hà Nội</span>
                    </li>
                    <li class="border-bottom pb-2">
                        <strong class="text-dark">TP. Hồ Chí Minh:</strong><br>
                        <span class="text-muted">456 Lê Văn Sỹ, Phường 14, Quận 3, TP. Hồ Chí Minh</span>
                    </li>
                    <li class="border-bottom pb-2">
                        <strong class="text-dark">Đà Nẵng:</strong><br>
                        <span class="text-muted">78 Bạch Đằng, P. Thạch Thang, Q. Hải Châu, Đà Nẵng</span>
                    </li>
                    <li>
                        <strong class="text-dark">Cần Thơ:</strong><br>
                        <span class="text-muted">15 Đại lộ Hòa Bình, P. An Cư, Q. Ninh Kiều, Cần Thơ</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Google Map Embed -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mt-4">
        <div class="p-3 bg-light border-bottom fw-bold text-secondary">
            <i class="fa-solid fa-map-location-dot me-2 text-danger"></i> Vị Trí Showroom Chính Trên Bản Đồ
        </div>
        <div class="ratio ratio-21x9" style="min-height: 350px;">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3723.8638558814234!2d105.79058287596985!3d21.038132787453303!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3135ab3b44b82d9f%3A0xe7a5089e90059b0d!2zQ-G6p3UgR2nhuqV5LCBIw6AgTuG7mWksIFZp4buHdCBOYW0!5e0!3m2!1svi!2s!4v1700000000000!5m2!1svi!2s" 
                    width="100%" 
                    height="100%" 
                    style="border:0;" 
                    allowfullscreen="" 
                    loading="lazy"></iframe>
        </div>
    </div>
</div>
@endsection
