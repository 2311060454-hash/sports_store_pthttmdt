@extends('layouts.app')

@section('title', 'Chính Sách Bán Hàng & Hoạt Động - Sport Store')

@section('content')
<!-- Page Header -->
<div class="bg-dark text-white py-5 mb-5" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);">
    <div class="container text-center py-4">
        <span class="badge bg-warning text-dark px-3 py-2 fw-bold text-uppercase mb-3">Trung Thực & Minh Bạch</span>
        <h1 class="display-5 fw-extrabold mb-3">Chính Sách & Quy Định Hoạt Động</h1>
        <p class="lead text-light opacity-75 max-w-700 mx-auto" style="max-width: 700px;">
            Cam kết mang đến dịch vụ mua sắm dụng cụ thể thao minh bạch, bảo vệ tối đa quyền lợi người tiêu dùng.
        </p>
    </div>
</div>

<div class="container py-3 mb-5">
    <div class="row g-4">
        <!-- Sidebar Navigation -->
        <div class="col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 sticky-top" style="top: 90px;">
                <div class="nav flex-column nav-pills" id="policy-pills" role="tablist">
                    <button class="nav-link active text-start py-3 fw-bold rounded-3 mb-2" id="v-pills-shipping-tab" data-bs-toggle="pill" data-bs-target="#v-pills-shipping" type="button" role="tab">
                        <i class="fa-solid fa-truck-fast me-2 text-primary"></i> 1. Giao Hàng & Đồng Kiểm
                    </button>
                    <button class="nav-link text-start py-3 fw-bold rounded-3 mb-2" id="v-pills-return-tab" data-bs-toggle="pill" data-bs-target="#v-pills-return" type="button" role="tab">
                        <i class="fa-solid fa-rotate-left me-2 text-danger"></i> 2. Đổi Trả Trong 30 Ngày
                    </button>
                    <button class="nav-link text-start py-3 fw-bold rounded-3 mb-2" id="v-pills-warranty-tab" data-bs-toggle="pill" data-bs-target="#v-pills-warranty" type="button" role="tab">
                        <i class="fa-solid fa-award me-2 text-warning"></i> 3. Bảo Hành Chính Hãng
                    </button>
                    <button class="nav-link text-start py-3 fw-bold rounded-3" id="v-pills-privacy-tab" data-bs-toggle="pill" data-bs-target="#v-pills-privacy" type="button" role="tab">
                        <i class="fa-solid fa-shield-halved me-2 text-success"></i> 4. Bảo Mật Thông Tin
                    </button>
                </div>
            </div>
        </div>

        <!-- Policy Content Panes -->
        <div class="col-lg-9">
            <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5">
                <div class="tab-content" id="policy-tabContent">
                    <!-- Pane 1: Vận chuyển -->
                    <div class="tab-pane fade show active" id="v-pills-shipping" role="tabpanel">
                        <h3 class="fw-extrabold mb-4 pb-2 border-bottom text-primary">
                            <i class="fa-solid fa-truck-fast me-2"></i> Chính Sách Giao Nhận & Đồng Kiểm Hàng
                        </h3>
                        <div class="lh-lg text-secondary">
                            <h5>1. Đối tác vận chuyển</h5>
                            <p>Sport Store hợp tác chiến lược cùng <strong>Giao Hàng Nhanh (GHN)</strong> nhằm đảm bảo bưu phẩm được vận chuyển chuyên nghiệp, mã vận đơn tự động đồng bộ trên hệ thống giúp quý khách tra cứu lộ trình 24/7.</p>
                            
                            <h5>2. Quyền lợi đồng kiểm khi nhận hàng</h5>
                            <p>Tất cả các đơn hàng gửi đi từ Sport Store đều cho phép người nhận <strong>ĐỒNG KIỂM HÀNG</strong> trước khi thanh toán:</p>
                            <ul>
                                <li>Quý khách được kiểm tra ngoại quan bưu phẩm, đúng mẫu mã, đúng size, màu sắc và số lượng đã đặt.</li>
                                <li>Không thử đồ hoặc can thiệp cắt tem mác trong lúc đồng kiểm.</li>
                                <li>Nếu phát hiện hàng bị móp méo, rách vỡ hoặc sai sản phẩm, quý khách có quyền từ chối nhận hàng mà không mất bất kỳ khoản phí nào.</li>
                            </ul>

                            <h5>3. Biểu phí và thời gian giao hàng</h5>
                            <p>Cước phí vận chuyển được tính minh bạch theo bảng cước niêm yết của GHN. Đơn hàng có giá trị từ <strong>500.000 VNĐ</strong> trở lên được hỗ trợ miễn phí vận chuyển trên toàn quốc.</p>
                        </div>
                    </div>

                    <!-- Pane 2: Đổi trả -->
                    <div class="tab-pane fade" id="v-pills-return" role="tabpanel">
                        <h3 class="fw-extrabold mb-4 pb-2 border-bottom text-danger">
                            <i class="fa-solid fa-rotate-left me-2"></i> Chính Sách Đổi Trả Hàng Linh Hoạt 30 Ngày
                        </h3>
                        <div class="lh-lg text-secondary">
                            <h5>1. Điều kiện áp dụng đổi trả</h5>
                            <p>Sport Store cam kết hỗ trợ đổi trả sản phẩm trong vòng <strong>30 ngày</strong> kể từ ngày quý khách nhận hàng nếu đáp ứng đủ các tiêu chí:</p>
                            <ul>
                                <li>Sản phẩm còn nguyên tem, mác, hộp bao bì của nhà sản xuất.</li>
                                <li>Chưa qua sử dụng, chưa giặt tẩy, không có mùi lạ và không bị trầy xước.</li>
                                <li>Có mã đơn hàng hoặc hóa đơn mua hàng điện tử tại Sport Store.</li>
                            </ul>

                            <h5>2. Quy trình đổi hàng</h5>
                            <p>Quý khách chỉ cần liên hệ hotline <strong>1900 8888</strong> hoặc nhắn tin qua mục <strong>Live Chat</strong> trên website. Đội ngũ nhân viên sẽ điều phối shipper GHN đến tận nhà để nhận lại hàng cần đổi hoàn toàn tiện lợi.</p>
                        </div>
                    </div>

                    <!-- Pane 3: Bảo hành -->
                    <div class="tab-pane fade" id="v-pills-warranty" role="tabpanel">
                        <h3 class="fw-extrabold mb-4 pb-2 border-bottom text-warning">
                            <i class="fa-solid fa-award me-2"></i> Chính Sách Bảo Hành Chính Hãng 12 Tháng
                        </h3>
                        <div class="lh-lg text-secondary">
                            <h5>1. Phạm vi bảo hành</h5>
                            <p>Toàn bộ vợt cầu lông, vợt tennis, máy chạy bộ, giày thể thao chính hãng đều được bảo hành chính hãng từ <strong>6 đến 12 tháng</strong> tùy theo chính sách từng thương hiệu (Yonex, Nike, Adidas, Wilson...).</p>
                            
                            <h5>2. Các trường hợp được bảo hành miễn phí</h5>
                            <ul>
                                <li>Vợt bị rạn nứt khung do lỗi đúc của nhà sản xuất (không phải do va chạm cạch vợt).</li>
                                <li>Giày thể thao bị bung keo, bung chỉ may trong thời hạn 90 ngày đầu tiên.</li>
                                <li>Đồng hồ thông minh hoặc phụ kiện điện tử thể thao lỗi bo mạch, pin không sạc được.</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Pane 4: Bảo mật -->
                    <div class="tab-pane fade" id="v-pills-privacy" role="tabpanel">
                        <h3 class="fw-extrabold mb-4 pb-2 border-bottom text-success">
                            <i class="fa-solid fa-shield-halved me-2"></i> Chính Sách Bảo Mật Thông Tin Khách Hàng
                        </h3>
                        <div class="lh-lg text-secondary">
                            <h5>1. Mục đích thu thập dữ liệu</h5>
                            <p>Sport Store chỉ thu thập các thông tin tối thiểu cần thiết để phục vụ xử lý đơn hàng, xuất hóa đơn điện tử và liên hệ giao hàng gồm: Họ tên, Số điện thoại, Email và Địa chỉ giao hàng.</p>
                            
                            <h5>2. Cam kết an toàn dữ liệu</h5>
                            <p>Mật khẩu tài khoản được mã hóa một chiều bằng chuẩn <strong>Bcrypt</strong> hiện đại. Toàn bộ thông tin thanh toán MoMo hay thẻ ngân hàng đều được xử lý qua cổng bảo mật chuẩn quốc tế PCI-DSS, chúng tôi hoàn toàn không lưu trữ thông tin thẻ của quý khách trên máy chủ.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
