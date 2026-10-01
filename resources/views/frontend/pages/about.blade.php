@extends('layouts.app')

@section('title', 'Về Chúng Tôi - Sport Store')

@section('content')
<!-- Page Header -->
<div class="bg-dark text-white py-5 mb-5" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);">
    <div class="container text-center py-4">
        <span class="badge bg-warning text-dark px-3 py-2 fw-bold text-uppercase mb-3">Về Sport Store</span>
        <h1 class="display-4 fw-extrabold mb-3">Đồng Hành Cùng Đam Mê Thể Thao Của Bạn</h1>
        <p class="lead text-light opacity-75 max-w-700 mx-auto" style="max-width: 700px;">
            Nhà phân phối dụng cụ và trang phục thể thao chính hãng hàng đầu Việt Nam, mang đến giải pháp tập luyện tối ưu và truyền cảm hứng lối sống tích cực.
        </p>
    </div>
</div>

<div class="container py-3">
    <!-- Story & Mission -->
    <div class="row align-items-center g-5 mb-5 pb-4">
        <div class="col-lg-6">
            <span class="text-primary fw-bold text-uppercase" style="letter-spacing: 1px;">Câu chuyện thương hiệu</span>
            <h2 class="fw-extrabold mb-3 mt-1">Hành Trình Kiến Tạo Nền Tảng Thể Lực Việt</h2>
            <p class="text-muted leading-relaxed">
                Được thành lập từ năm 2018 với xuất phát điểm từ tình yêu mãnh liệt dành cho thể thao, <strong>Sport Store</strong> đã không ngừng vươn mình trở thành điểm đến mua sắm uy tín cho hàng triệu người yêu vận động, từ người tập phong trào đến các vận động viên chuyên nghiệp.
            </p>
            <p class="text-muted leading-relaxed">
                Chúng tôi tự hào là đối tác chiến lược của những thương hiệu hàng đầu thế giới như <strong>Nike, Adidas, Puma, Yonex, Li-Ning, Wilson, Mikasa, Molten</strong>. Mỗi sản phẩm được bán ra đều là kết tinh của sự chính xác, độ bền cao và trải nghiệm người dùng vượt bậc.
            </p>
            <div class="row g-3 mt-2">
                <div class="col-sm-6">
                    <div class="d-flex align-items-center">
                        <i class="fa-solid fa-circle-check text-success fs-4 me-2"></i>
                        <span class="fw-semibold">100% Hàng chính hãng</span>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="d-flex align-items-center">
                        <i class="fa-solid fa-circle-check text-success fs-4 me-2"></i>
                        <span class="fw-semibold">Đổi trả 30 ngày linh hoạt</span>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="d-flex align-items-center">
                        <i class="fa-solid fa-circle-check text-success fs-4 me-2"></i>
                        <span class="fw-semibold">Giao hàng hỏa tốc toàn quốc</span>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="d-flex align-items-center">
                        <i class="fa-solid fa-circle-check text-success fs-4 me-2"></i>
                        <span class="fw-semibold">Bảo hành chính hãng 12 tháng</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="position-relative">
                <img src="https://images.unsplash.com/photo-1517649763962-0c623266ddc0?w=800&auto=format&fit=crop&q=80" 
                     alt="Về chúng tôi" 
                     class="img-fluid rounded-4 shadow-lg w-100" 
                     style="object-fit: cover; max-height: 440px;">
                <div class="position-absolute bottom-0 start-0 translate-middle-y bg-white rounded-3 shadow p-3 m-4 border-start border-4 border-primary">
                    <div class="fw-extrabold fs-3 text-primary">50.000+</div>
                    <div class="small text-muted fw-semibold">Khách hàng tin tưởng ủng hộ</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Counter -->
    <div class="row g-4 text-center mb-5 pb-4">
        <div class="col-md-3 col-6">
            <div class="card border-0 bg-light rounded-4 p-4 h-100">
                <div class="fs-1 fw-extrabold text-primary mb-1">8+</div>
                <div class="fw-bold text-dark">Năm Hoạt Động</div>
                <small class="text-muted">Kinh nghiệm phục vụ thể thao</small>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card border-0 bg-light rounded-4 p-4 h-100">
                <div class="fs-1 fw-extrabold text-success mb-1">500+</div>
                <div class="fw-bold text-dark">Dụng Cụ Thể Thao</div>
                <small class="text-muted">Đa dạng mẫu mã các bộ môn</small>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card border-0 bg-light rounded-4 p-4 h-100">
                <div class="fs-1 fw-extrabold text-warning mb-1">100%</div>
                <div class="fw-bold text-dark">Chính Hãng</div>
                <small class="text-muted">Nguồn gốc xuất xứ rõ ràng</small>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card border-0 bg-light rounded-4 p-4 h-100">
                <div class="fs-1 fw-extrabold text-danger mb-1">24/7</div>
                <div class="fw-bold text-dark">Hỗ Trợ Tận Tâm</div>
                <small class="text-muted">Tư vấn chọn size chuẩn xác</small>
            </div>
        </div>
    </div>

    <!-- Core Values -->
    <div class="text-center mb-5">
        <span class="text-primary fw-bold text-uppercase" style="letter-spacing: 1px;">Giá trị cốt lõi</span>
        <h2 class="fw-extrabold mb-4 mt-1">Vì Sao Khách Hàng Chọn Sport Store?</h2>
    </div>

    <div class="row g-4 mb-5 pb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 p-4 h-100 text-center">
                <div class="rounded-circle bg-primary-subtle text-primary d-inline-flex align-items-center justify-content-center mx-auto mb-3" style="width: 70px; height: 70px;">
                    <i class="fa-solid fa-award fs-2"></i>
                </div>
                <h5 class="fw-bold mb-2">Chất Lượng Thượng Hạng</h5>
                <p class="text-muted small mb-0">Cam kết chỉ phân phối sản phẩm chính hãng, được tuyển chọn từ các nhà sản xuất thể thao số 1 thế giới.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 p-4 h-100 text-center">
                <div class="rounded-circle bg-success-subtle text-success d-inline-flex align-items-center justify-content-center mx-auto mb-3" style="width: 70px; height: 70px;">
                    <i class="fa-solid fa-tags fs-2"></i>
                </div>
                <h5 class="fw-bold mb-2">Giá Tốt & Nhiều Ưu Đãi</h5>
                <p class="text-muted small mb-0">Chương trình khuyến mãi liên tục, mã giảm giá phong phú, cam kết mức giá cạnh tranh nhất thị trường.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 p-4 h-100 text-center">
                <div class="rounded-circle bg-warning-subtle text-warning d-inline-flex align-items-center justify-content-center mx-auto mb-3" style="width: 70px; height: 70px;">
                    <i class="fa-solid fa-headset fs-2"></i>
                </div>
                <h5 class="fw-bold mb-2">Dịch Vụ Hậu Mãi Chu Đáo</h5>
                <p class="text-muted small mb-0">Hỗ trợ đổi trả trong 30 ngày nếu không vừa size, bảo hành kỹ thuật căng cước vợt và dán keo miễn phí trọn đời.</p>
            </div>
        </div>
    </div>
</div>
@endsection
