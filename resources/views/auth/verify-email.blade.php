@extends('layouts.app')

@section('title', 'Xác Thực Địa Chỉ Email - Sport Store')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-6">
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="card-header bg-gradient bg-primary text-white text-center py-4 border-0">
                    <div class="mb-2">
                        <div class="rounded-circle bg-white text-primary d-inline-flex align-items-center justify-content-center shadow-sm" style="width: 70px; height: 70px;">
                            <i class="fa-solid fa-envelope-circle-check fs-2"></i>
                        </div>
                    </div>
                    <h4 class="fw-bold mb-1">Xác Thực Địa Chỉ Email</h4>
                    <p class="mb-0 opacity-75 small">Kích hoạt tài khoản mua sắm tại Sport Store</p>
                </div>

                <div class="card-body p-4 p-md-5">
                    @if (session('status'))
                        <div class="alert alert-success alert-dismissible fade show rounded-3" role="alert">
                            <i class="fa-solid fa-circle-check me-2"></i> {{ session('status') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <p class="text-secondary mb-4 leading-relaxed">
                        Cảm ơn bạn đã gia nhập cộng đồng <strong>Sport Store</strong>! Trước khi bắt đầu, vui lòng kiểm tra hộp thư đến tại địa chỉ 
                        <strong class="text-dark font-monospace">{{ auth()->user()->email }}</strong> và nhấn vào liên kết chúng tôi đã gửi để xác minh tài khoản.
                    </p>

                    <div class="p-3 bg-light rounded-3 border mb-4">
                        <div class="d-flex align-items-start gap-2">
                            <i class="fa-solid fa-circle-info text-primary mt-1"></i>
                            <div class="small text-muted">
                                Nếu không thấy email trong Hộp thư đến (Inbox), vui lòng kiểm tra cả thư mục <strong>Spam / Rác / Quảng cáo</strong>.
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="d-flex flex-column gap-3 mb-4">
                        <form method="POST" action="{{ route('verification.send') }}">
                            @csrf
                            <button type="submit" class="btn btn-outline-primary w-100 py-2 rounded-pill fw-semibold">
                                <i class="fa-solid fa-paper-plane me-2"></i> Gửi lại email xác thực
                            </button>
                        </form>
                    </div>

                    <!-- Quick Demo Verification For Evaluation -->
                    <div class="card border border-warning bg-warning bg-opacity-10 rounded-3 p-3 mb-3">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <i class="fa-solid fa-bolt-lightning text-warning fs-5"></i>
                            <h6 class="fw-bold mb-0 text-dark">Dành cho Giáo viên chấm bài / Trải nghiệm Demo</h6>
                        </div>
                        <p class="small text-muted mb-3">
                            Nếu đang chạy trên môi trường thử nghiệm không cấu hình máy chủ SMTP thật, quý Thầy/Cô có thể bấm nút bên dưới để xác thực tài khoản ngay lập tức:
                        </p>
                        <form method="POST" action="{{ route('verification.instant') }}">
                            @csrf
                            <button type="submit" class="btn btn-warning w-100 py-2 rounded-pill fw-bold text-dark shadow-sm">
                                <i class="fa-solid fa-check-double me-2"></i> Kích Hoạt & Xác Thực Email Ngay (1-Click)
                            </button>
                        </form>
                    </div>

                    <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                        <a href="{{ route('account.profile') }}" class="text-decoration-none small text-muted">
                            <i class="fa-solid fa-user me-1"></i> Về trang tài khoản
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn btn-link text-danger p-0 small text-decoration-none">
                                <i class="fa-solid fa-right-from-bracket me-1"></i> Đăng xuất
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
