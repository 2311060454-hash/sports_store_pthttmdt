@extends('layouts.app')

@section('title', 'Câu Hỏi Thường Gặp (FAQ) - Sport Store')

@section('content')
<!-- Page Header -->
<div class="bg-dark text-white py-5 mb-5" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);">
    <div class="container text-center py-4">
        <span class="badge bg-warning text-dark px-3 py-2 fw-bold text-uppercase mb-3">Trung Tâm Trợ Giúp</span>
        <h1 class="display-5 fw-extrabold mb-3">Câu Hỏi Thường Gặp (FAQ)</h1>
        <p class="lead text-light opacity-75 max-w-700 mx-auto" style="max-width: 700px;">
            Giải đáp nhanh chóng các thắc mắc phổ biến về đặt hàng, thanh toán MoMo/COD, đối tác vận chuyển GHN và chính sách bảo hành.
        </p>
    </div>
</div>

<div class="container py-3 mb-5">
    <div class="row g-4">
        <!-- Sidebar Navigation -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 p-4 sticky-top" style="top: 90px;">
                <h5 class="fw-bold mb-3"><i class="fa-solid fa-list-check text-primary me-2"></i> Danh Mục Hỗ Trợ</h5>
                <div class="list-group list-group-flush small">
                    <a href="#cat-order" class="list-group-item list-group-item-action border-0 py-2 fw-semibold text-dark">
                        <i class="fa-solid fa-cart-shopping text-primary me-2"></i> 1. Đặt hàng & Giỏ hàng
                    </a>
                    <a href="#cat-payment" class="list-group-item list-group-item-action border-0 py-2 fw-semibold text-dark">
                        <i class="fa-solid fa-credit-card text-success me-2"></i> 2. Thanh toán (MoMo & COD)
                    </a>
                    <a href="#cat-shipping" class="list-group-item list-group-item-action border-0 py-2 fw-semibold text-dark">
                        <i class="fa-solid fa-truck-fast text-warning me-2"></i> 3. Vận chuyển & Giao Hàng Nhanh
                    </a>
                    <a href="#cat-return" class="list-group-item list-group-item-action border-0 py-2 fw-semibold text-dark">
                        <i class="fa-solid fa-rotate-left text-danger me-2"></i> 4. Đổi trả & Bảo hành
                    </a>
                    <a href="#cat-account" class="list-group-item list-group-item-action border-0 py-2 fw-semibold text-dark">
                        <i class="fa-solid fa-user-shield text-info me-2"></i> 5. Tài khoản & Tích điểm
                    </a>
                </div>

                <div class="mt-4 pt-3 border-top text-center">
                    <p class="small text-muted mb-2">Chưa tìm thấy câu trả lời?</p>
                    <a href="{{ route('contact') }}" class="btn btn-outline-primary btn-sm rounded-pill w-100 fw-bold">
                        <i class="fa-solid fa-headset me-1"></i> Liên hệ đội ngũ CSKH
                    </a>
                </div>
            </div>
        </div>

        <!-- Accordion Q&A Content -->
        <div class="col-lg-8">
            <!-- Section 1: Đặt hàng -->
            <div id="cat-order" class="mb-5">
                <h4 class="fw-extrabold mb-3 d-flex align-items-center">
                    <span class="badge bg-primary me-2">1</span> Đặt Hàng & Giỏ Hàng
                </h4>
                <div class="accordion shadow-sm rounded-4 overflow-hidden" id="accordionOrder">
                    <div class="accordion-item border-0 border-bottom">
                        <h2 class="accordion-header" id="headingO1">
                            <button class="accordion-button fw-bold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseO1">
                                Tôi có cần đăng ký tài khoản để mua hàng không?
                            </button>
                        </h2>
                        <div id="collapseO1" class="accordion-collapse collapse" data-bs-parent="#accordionOrder">
                            <div class="accordion-body text-secondary">
                                Bạn có thể tham khảo sản phẩm thoải mái, nhưng để đảm bảo quyền lợi tích điểm thành viên, theo dõi trạng thái vận chuyển và nhận ưu đãi bảo hành, hệ thống khuyến khích bạn đăng ký một tài khoản thành viên trong vòng 30 giây.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item border-0 border-bottom">
                        <h2 class="accordion-header" id="headingO2">
                            <button class="accordion-button fw-bold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseO2">
                                Tôi có thể hủy đơn hàng sau khi đã đặt thành công không?
                            </button>
                        </h2>
                        <div id="collapseO2" class="accordion-collapse collapse" data-bs-parent="#accordionOrder">
                            <div class="accordion-body text-secondary">
                                Bạn hoàn toàn có thể tự hủy đơn hàng trực tiếp trong trang <strong>Lịch sử đơn hàng</strong> khi đơn hàng đang ở trạng thái <em>Chờ xử lý (Pending)</em> hoặc <em>Đang đóng gói</em>. Tuy nhiên, khi đơn đã bàn giao sang đối tác vận chuyển (đang giao hàng), hệ thống sẽ chặn chức năng hủy tự động theo quy định phối hợp cùng đơn vị vận chuyển.
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 2: Thanh toán -->
            <div id="cat-payment" class="mb-5">
                <h4 class="fw-extrabold mb-3 d-flex align-items-center">
                    <span class="badge bg-success me-2">2</span> Thanh Toán (MoMo, COD & Thẻ)
                </h4>
                <div class="accordion shadow-sm rounded-4 overflow-hidden" id="accordionPayment">
                    <div class="accordion-item border-0 border-bottom">
                        <h2 class="accordion-header" id="headingP1">
                            <button class="accordion-button fw-bold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseP1">
                                Sport Store hỗ trợ những phương thức thanh toán nào?
                            </button>
                        </h2>
                        <div id="collapseP1" class="accordion-collapse collapse" data-bs-parent="#accordionPayment">
                            <div class="accordion-body text-secondary">
                                Chúng tôi hỗ trợ đa dạng phương thức thanh toán an toàn 100%:
                                <ul>
                                    <li><strong>Thanh toán khi nhận hàng (COD):</strong> Thanh toán tiền mặt cho shipper GHN khi nhận bưu phẩm.</li>
                                    <li><strong>Ví điện tử MoMo:</strong> Quét mã QR MoMo hoặc thanh toán bằng ứng dụng MoMo chỉ với 1 chạm.</li>
                                    <li><strong>Thẻ ATM nội địa / Internet Banking:</strong> Cổng thanh toán MoMo hỗ trợ hơn 30 ngân hàng lớn tại Việt Nam.</li>
                                    <li><strong>Chuyển khoản trực tiếp:</strong> Chuyển tiền qua tài khoản ngân hàng chính thức của cửa hàng.</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item border-0 border-bottom">
                        <h2 class="accordion-header" id="headingP2">
                            <button class="accordion-button fw-bold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseP2">
                                Nếu thanh toán MoMo bị gián đoạn hoặc lỗi mạng thì sao?
                            </button>
                        </h2>
                        <div id="collapseP2" class="accordion-collapse collapse" data-bs-parent="#accordionPayment">
                            <div class="accordion-body text-secondary">
                                Nếu phiên thanh toán MoMo hết hạn hoặc bị ngắt kết nối, đơn hàng của bạn vẫn được lưu an toàn. Bạn chỉ cần vào <strong>Lịch sử đơn hàng</strong> và bấm nút <strong>"Thanh toán lại qua MoMo"</strong> để hoàn tất bất cứ lúc nào.
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 3: Vận chuyển GHN -->
            <div id="cat-shipping" class="mb-5">
                <h4 class="fw-extrabold mb-3 d-flex align-items-center">
                    <span class="badge bg-warning text-dark me-2">3</span> Vận Chuyển & Giao Hàng Nhanh (GHN)
                </h4>
                <div class="accordion shadow-sm rounded-4 overflow-hidden" id="accordionShipping">
                    <div class="accordion-item border-0 border-bottom">
                        <h2 class="accordion-header" id="headingS1">
                            <button class="accordion-button fw-bold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseS1">
                                Cước phí vận chuyển được tính như thế nào?
                            </button>
                        </h2>
                        <div id="collapseS1" class="accordion-collapse collapse" data-bs-parent="#accordionShipping">
                            <div class="accordion-body text-secondary">
                                Phí vận chuyển được hệ thống tính tự động theo thời gian thực kết nối trực tiếp với <strong>API Giao Hàng Nhanh (GHN)</strong> dựa trên trọng lượng bưu phẩm và khoảng cách địa lý (Tỉnh/Thành phố, Quận/Huyện, Phường/Xã). Quý khách có thể sử dụng trang <a href="{{ route('shipping.calculator') }}" class="text-primary fw-bold">Tra cứu cước phí GHN</a> để tính trước chi phí.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item border-0 border-bottom">
                        <h2 class="accordion-header" id="headingS2">
                            <button class="accordion-button fw-bold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseS2">
                                Thời gian giao hàng dự kiến là bao lâu?
                            </button>
                        </h2>
                        <div id="collapseS2" class="accordion-collapse collapse" data-bs-parent="#accordionShipping">
                            <div class="accordion-body text-secondary">
                                <ul>
                                    <li><strong>Nội thành TP. Hồ Chí Minh & Hà Nội:</strong> Nhận hàng trong 24 giờ - 48 giờ.</li>
                                    <li><strong>Các tỉnh thành khác:</strong> Nhận hàng từ 2 - 4 ngày làm việc.</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 4: Đổi trả -->
            <div id="cat-return" class="mb-5">
                <h4 class="fw-extrabold mb-3 d-flex align-items-center">
                    <span class="badge bg-danger me-2">4</span> Đổi Trả & Bảo Hành
                </h4>
                <div class="accordion shadow-sm rounded-4 overflow-hidden" id="accordionReturn">
                    <div class="accordion-item border-0 border-bottom">
                        <h2 class="accordion-header" id="headingR1">
                            <button class="accordion-button fw-bold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseR1">
                                Chính sách đổi trả hàng trong 30 ngày áp dụng thế nào?
                            </button>
                        </h2>
                        <div id="collapseR1" class="accordion-collapse collapse" data-bs-parent="#accordionReturn">
                            <div class="accordion-body text-secondary">
                                Khách hàng được đổi sang size khác hoặc sản phẩm tương đương trong vòng 30 ngày kể từ khi nhận hàng nếu:
                                <ul>
                                    <li>Sản phẩm còn nguyên tem mác, hộp bao bì, chưa qua sử dụng hoặc giặt tẩy.</li>
                                    <li>Sản phẩm bị lỗi kỹ thuật do nhà sản xuất (bung keo, rạn nứt khung vợt khi mới khui hộp).</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 5: Tài khoản & Điểm thưởng -->
            <div id="cat-account" class="mb-5">
                <h4 class="fw-extrabold mb-3 d-flex align-items-center">
                    <span class="badge bg-info text-white me-2">5</span> Tài Khoản & Điểm Thưởng Thành Viên
                </h4>
                <div class="accordion shadow-sm rounded-4 overflow-hidden" id="accordionAccount">
                    <div class="accordion-item border-0 border-bottom">
                        <h2 class="accordion-header" id="headingA1">
                            <button class="accordion-button fw-bold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseA1">
                                Chương trình khách hàng thân thiết hoạt động ra sao?
                            </button>
                        </h2>
                        <div id="collapseA1" class="accordion-collapse collapse" data-bs-parent="#accordionAccount">
                            <div class="accordion-body text-secondary">
                                Mỗi 10.000đ chi tiêu trên các đơn hàng hoàn thành, bạn tích lũy được 1 điểm thưởng. Khi đạt đủ điểm mốc, hạng thành viên sẽ tự động nâng từ <strong>Đồng $\rightarrow$ Bạc $\rightarrow$ Vàng $\rightarrow$ Kim Cương</strong> với các quyền lợi freeship và voucher giảm giá đặc biệt.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
