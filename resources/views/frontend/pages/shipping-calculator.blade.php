@extends('layouts.app')

@section('title', 'Tra Cứu & Tính Cước Phí Vận Chuyển GHN - Sport Store')

@section('content')
<div class="container py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-muted">Trang chủ</a></li>
            <li class="breadcrumb-item active text-dark fw-semibold" aria-current="page">Tra cứu cước phí vận chuyển</li>
        </ol>
    </nav>

    <!-- Page Title & Header -->
    <div class="row align-items-center mb-4">
        <div class="col-lg-8">
            <div class="d-flex align-items-center gap-3">
                <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center flex-shrink-0" style="width: 55px; height: 55px;">
                    <i class="fa-solid fa-calculator fs-3"></i>
                </div>
                <div>
                    <h3 class="fw-extrabold mb-1">Tra Cứu & Tính Cước Phí Vận Chuyển</h3>
                    <p class="text-muted mb-0 small">Báo giá cước giao hàng nhanh toàn quốc kết nối trực tiếp với hệ thống máy chủ <strong>Giao Hàng Nhanh (GHN)</strong></p>
                </div>
            </div>
        </div>
        <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
            <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-2 fs-6">
                <i class="fa-solid fa-truck-fast me-1"></i> Đối tác chính thức: GHN Express
            </span>
        </div>
    </div>

    <div class="row g-4">
        <!-- Form Calculator (Left Column) -->
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 mb-4">
                <h5 class="fw-bold mb-4 border-bottom pb-2">
                    <i class="fa-solid fa-map-location-dot text-primary me-2"></i> Thông Tin Điểm Gửi & Nhận
                </h5>

                <form id="calculatorForm">
                    <!-- Origin Warehouse (Fixed) -->
                    <div class="p-3 bg-light rounded-3 border mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="small fw-bold text-uppercase text-muted"><i class="fa-solid fa-warehouse me-1 text-primary"></i> Kho hàng gửi đi (Kho Sport Store)</span>
                            <span class="badge bg-primary rounded-pill">Kho mặc định</span>
                        </div>
                        <div class="fw-bold text-dark">{{ $shopInfo['name'] }}</div>
                        <div class="small text-secondary"><i class="fa-solid fa-location-dot me-1"></i> {{ $shopInfo['address'] }}</div>
                    </div>

                    <!-- Destination Selectors -->
                    <h6 class="fw-bold mb-3 small text-uppercase text-primary"><i class="fa-solid fa-location-crosshairs me-1"></i> Nơi nhận hàng (Người mua)</h6>
                    
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Tỉnh / Thành phố <span class="text-danger">*</span></label>
                            <select id="calc_province" class="form-select rounded-3">
                                <option value="">-- Đang tải... --</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Quận / Huyện <span class="text-danger">*</span></label>
                            <select id="calc_district" class="form-select rounded-3" disabled>
                                <option value="">-- Chọn Quận/Huyện --</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Phường / Xã <span class="text-danger">*</span></label>
                            <select id="calc_ward" class="form-select rounded-3" disabled>
                                <option value="">-- Chọn Phường/Xã --</option>
                            </select>
                        </div>
                    </div>

                    <!-- Package Specifications -->
                    <h5 class="fw-bold mb-3 border-bottom pb-2">
                        <i class="fa-solid fa-box text-primary me-2"></i> Thông Số Kiện Hàng Thể Thao
                    </h5>

                    <!-- Quick Presets -->
                    <div class="mb-3">
                        <label class="form-label small text-muted mb-2">Chọn nhanh loại sản phẩm thường gửi:</label>
                        <div class="d-flex flex-wrap gap-2">
                            <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill js-preset-weight" data-weight="300" data-l="25" data-w="15" data-h="5">
                                👕 Áo/Quần thể thao (300g)
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill js-preset-weight" data-weight="500" data-l="68" data-w="22" data-h="5">
                                🏸 Vợt cầu lông (500g)
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill js-preset-weight" data-weight="900" data-l="32" data-w="20" data-h="12">
                                👟 Giày thể thao (900g)
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill js-preset-weight" data-weight="600" data-l="22" data-w="22" data-h="22">
                                ⚽ Quả bóng đá/bóng rổ (600g)
                            </button>
                        </div>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Trọng lượng kiện hàng (Gram) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" id="calc_weight" class="form-control rounded-start-3" value="500" min="50" max="50000" required>
                                <span class="input-group-text">gram</span>
                            </div>
                            <small class="text-muted" style="font-size: 0.75rem;">1kg = 1.000g</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Giá trị đơn hàng (Bảo hiểm) <span class="text-muted">(VNĐ)</span></label>
                            <div class="input-group">
                                <input type="number" id="calc_insurance" class="form-control rounded-start-3" value="500000" min="0" step="50000">
                                <span class="input-group-text">₫</span>
                            </div>
                            <small class="text-muted" style="font-size: 0.75rem;">GHN đền bù 100% khi có sự cố</small>
                        </div>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-4">
                            <label class="form-label small text-muted">Chiều dài (cm)</label>
                            <input type="number" id="calc_length" class="form-control form-control-sm" value="25" min="1" max="200">
                        </div>
                        <div class="col-4">
                            <label class="form-label small text-muted">Chiều rộng (cm)</label>
                            <input type="number" id="calc_width" class="form-control form-control-sm" value="15" min="1" max="200">
                        </div>
                        <div class="col-4">
                            <label class="form-label small text-muted">Chiều cao (cm)</label>
                            <input type="number" id="calc_height" class="form-control form-control-sm" value="10" min="1" max="200">
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" id="btnSubmitCalc" class="btn btn-primary w-100 py-3 rounded-pill fw-bold fs-6 shadow">
                        <i class="fa-solid fa-calculator me-2"></i> Tính Cước Phí Vận Chuyển Ngay
                    </button>
                </form>
            </div>
        </div>

        <!-- Result Box (Right Column) -->
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 sticky-top" style="top: 90px;">
                <h5 class="fw-bold mb-4 border-bottom pb-2">
                    <i class="fa-solid fa-receipt text-primary me-2"></i> Kết Quả Tính Cước GHN
                </h5>

                <!-- Initial State Placeholder -->
                <div id="resultPlaceholder" class="text-center py-5 text-muted">
                    <div class="rounded-circle bg-light p-4 d-inline-flex mb-3">
                        <i class="fa-solid fa-truck-ramp-box fs-1 text-secondary"></i>
                    </div>
                    <h6>Chưa có thông tin cước phí</h6>
                    <p class="small text-muted mb-0">Vui lòng chọn Tỉnh/Thành, Quận/Huyện nhận và bấm <strong>"Tính Cước Phí Ngay"</strong> để xem bảng giá chính thức từ GHN.</p>
                </div>

                <!-- Loading State -->
                <div id="resultLoading" class="text-center py-5 d-none">
                    <div class="spinner-border text-primary mb-3" style="width: 3rem; height: 3rem;"></div>
                    <div class="fw-semibold text-dark">Đang kết nối cổng máy chủ GHN...</div>
                    <small class="text-muted">Đang áp dụng bảng giá cước chuẩn Sandbox 2026</small>
                </div>

                <!-- Result Content -->
                <div id="resultContent" class="d-none">
                    <!-- Grand Total Fee Banner -->
                    <div class="p-3 rounded-4 bg-primary text-white text-center mb-4 shadow-sm" style="background: linear-gradient(135deg, #ff5722 0%, #ff784e 100%);">
                        <div class="small text-white-50 text-uppercase fw-semibold mb-1">Tổng cước phí vận chuyển</div>
                        <div class="display-6 fw-extrabold mb-0" id="res_total_fee">0 ₫</div>
                        <small class="text-white-50 mt-1 d-block"><i class="fa-solid fa-circle-check text-warning me-1"></i> Đã bao gồm thuế VAT & phụ phí xăng dầu</small>
                    </div>

                    <!-- Breakdown Table -->
                    <div class="d-flex flex-column gap-2 mb-4 small">
                        <div class="d-flex justify-content-between pb-2 border-bottom">
                            <span class="text-muted">Cước chuyển phát tiêu chuẩn:</span>
                            <span class="fw-bold text-dark" id="res_service_fee">0 ₫</span>
                        </div>
                        <div class="d-flex justify-content-between pb-2 border-bottom">
                            <span class="text-muted">Phí bảo hiểm hàng hóa:</span>
                            <span class="fw-bold text-success" id="res_insurance_fee">0 ₫</span>
                        </div>
                        <div class="d-flex justify-content-between pb-2 border-bottom">
                            <span class="text-muted">Thời gian giao dự kiến:</span>
                            <span class="fw-bold text-primary" id="res_expected_time">1 - 2 ngày</span>
                        </div>
                        <div class="d-flex justify-content-between pb-2 border-bottom">
                            <span class="text-muted">Ngày giao hàng dự kiến:</span>
                            <span class="fw-bold text-dark" id="res_expected_date">--/--/----</span>
                        </div>
                        <div class="d-flex justify-content-between pb-2 border-bottom">
                            <span class="text-muted">Trọng lượng tính cước:</span>
                            <span class="fw-bold text-dark" id="res_weight">500 g</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Kích thước quy đổi:</span>
                            <span class="fw-semibold text-muted" id="res_dimensions">25 x 15 x 10 cm</span>
                        </div>
                    </div>

                    <!-- Delivery Flow Timeline -->
                    <div class="p-3 bg-light rounded-3 border mb-4">
                        <h6 class="fw-bold mb-2 small text-uppercase text-muted"><i class="fa-solid fa-route me-1 text-primary"></i> Quy trình giao nhận Sport Store & GHN</h6>
                        <ul class="list-unstyled mb-0 small ps-2">
                            <li class="mb-2"><i class="fa-solid fa-circle-check text-success me-1"></i> Bưu tá GHN lấy hàng tại kho Sport Store Cầu Giấy</li>
                            <li class="mb-2"><i class="fa-solid fa-circle-check text-success me-1"></i> Vận chuyển qua trung tâm phân loại tự động</li>
                            <li><i class="fa-solid fa-circle-check text-success me-1"></i> Phát hàng tận tay - Cho phép đồng kiểm sản phẩm</li>
                        </ul>
                    </div>

                    <!-- Call To Action -->
                    <a href="{{ route('products.index') }}" class="btn btn-outline-primary w-100 rounded-pill py-2 fw-semibold">
                        <i class="fa-solid fa-bag-shopping me-1"></i> Mua Sắm Dụng Cụ Thể Thao Ngay
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function () {
    const provinceSelect = document.getElementById('calc_province');
    const districtSelect = document.getElementById('calc_district');
    const wardSelect = document.getElementById('calc_ward');
    const form = document.getElementById('calculatorForm');
    const btnSubmit = document.getElementById('btnSubmitCalc');

    const resultPlaceholder = document.getElementById('resultPlaceholder');
    const resultLoading = document.getElementById('resultLoading');
    const resultContent = document.getElementById('resultContent');

    // 1. Load Provinces from GHN
    fetch("{{ route('locations.provinces') }}")
        .then(res => res.json())
        .then(res => {
            if (res.data) {
                let options = '<option value="">-- Chọn Tỉnh/Thành --</option>';
                res.data.forEach(p => {
                    options += `<option value="${p.ProvinceID}">${p.ProvinceName}</option>`;
                });
                provinceSelect.innerHTML = options;
            } else {
                provinceSelect.innerHTML = '<option value="">-- Lỗi tải tỉnh thành --</option>';
            }
        })
        .catch(err => {
            console.error("Lỗi:", err);
            provinceSelect.innerHTML = '<option value="">-- Lỗi kết nối GHN --</option>';
        });

    // 2. Province change -> Load Districts
    provinceSelect.addEventListener('change', function () {
        districtSelect.innerHTML = '<option value="">-- Đang tải... --</option>';
        districtSelect.disabled = true;
        wardSelect.innerHTML = '<option value="">-- Chọn Phường/Xã --</option>';
        wardSelect.disabled = true;

        if (!this.value) return;

        fetch(`/locations/districts/${this.value}`)
            .then(res => res.json())
            .then(res => {
                if (res.data) {
                    let options = '<option value="">-- Chọn Quận/Huyện --</option>';
                    res.data.forEach(d => {
                        options += `<option value="${d.DistrictID}">${d.DistrictName}</option>`;
                    });
                    districtSelect.innerHTML = options;
                    districtSelect.disabled = false;
                }
            })
            .catch(err => console.error("Lỗi load quận:", err));
    });

    // 3. District change -> Load Wards
    districtSelect.addEventListener('change', function () {
        wardSelect.innerHTML = '<option value="">-- Đang tải... --</option>';
        wardSelect.disabled = true;

        if (!this.value) return;

        fetch(`/locations/wards/${this.value}`)
            .then(res => res.json())
            .then(res => {
                if (res.data) {
                    let options = '<option value="">-- Chọn Phường/Xã --</option>';
                    res.data.forEach(w => {
                        options += `<option value="${w.WardCode}">${w.WardName}</option>`;
                    });
                    wardSelect.innerHTML = options;
                    wardSelect.disabled = false;
                }
            })
            .catch(err => console.error("Lỗi load xã:", err));
    });

    // Preset buttons
    document.querySelectorAll('.js-preset-weight').forEach(btn => {
        btn.addEventListener('click', function () {
            document.getElementById('calc_weight').value = this.dataset.weight;
            document.getElementById('calc_length').value = this.dataset.l;
            document.getElementById('calc_width').value = this.dataset.w;
            document.getElementById('calc_height').value = this.dataset.h;

            document.querySelectorAll('.js-preset-weight').forEach(b => b.classList.remove('btn-primary', 'text-white'));
            this.classList.add('btn-primary', 'text-white');
        });
    });

    // Submit Calculator
    form.addEventListener('submit', function (e) {
        e.preventDefault();

        const districtId = districtSelect.value;
        const wardCode = wardSelect.value;

        if (!districtId || !wardCode) {
            Swal.fire({
                icon: 'warning',
                title: 'Thiếu thông tin nơi nhận',
                text: 'Vui lòng chọn đầy đủ Tỉnh/Thành, Quận/Huyện và Phường/Xã nơi nhận hàng.',
                confirmButtonColor: '#ff5722'
            });
            return;
        }

        resultPlaceholder.classList.add('d-none');
        resultContent.classList.add('d-none');
        resultLoading.classList.remove('d-none');
        btnSubmit.disabled = true;

        fetch("{{ route('locations.custom-fee') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                province_id: provinceSelect.value,
                to_district_id: districtId,
                to_ward_code: wardCode,
                weight: document.getElementById('calc_weight').value,
                length: document.getElementById('calc_length').value,
                width: document.getElementById('calc_width').value,
                height: document.getElementById('calc_height').value,
                insurance_value: document.getElementById('calc_insurance').value,
            })
        })
        .then(res => res.json())
        .then(data => {
            btnSubmit.disabled = false;
            resultLoading.classList.add('d-none');

            if (data.success && data.ghn_response && data.ghn_response.data) {
                resultContent.classList.remove('d-none');
                const ghn = data.ghn_response.data;

                const totalFee = ghn.total || 0;
                const serviceFee = ghn.service_fee || totalFee;
                const insuranceFee = ghn.insurance_fee || 0;

                document.getElementById('res_total_fee').innerText = new Intl.NumberFormat('vi-VN').format(totalFee) + ' ₫';
                document.getElementById('res_service_fee').innerText = new Intl.NumberFormat('vi-VN').format(serviceFee) + ' ₫';
                document.getElementById('res_insurance_fee').innerText = insuranceFee > 0 ? (new Intl.NumberFormat('vi-VN').format(insuranceFee) + ' ₫') : 'Miễn phí';
                document.getElementById('res_expected_time').innerText = data.expected_time;
                document.getElementById('res_expected_date').innerText = data.expected_date;
                document.getElementById('res_weight').innerText = new Intl.NumberFormat('vi-VN').format(data.weight) + ' g';
                document.getElementById('res_dimensions').innerText = data.dimensions;
            } else {
                resultPlaceholder.classList.remove('d-none');
                Swal.fire({
                    icon: 'error',
                    title: 'Không thể tính phí',
                    text: data.message || 'Tuyến giao hàng này hiện đang quá tải hoặc không hỗ trợ.',
                    confirmButtonColor: '#ff5722'
                });
            }
        })
        .catch(err => {
            btnSubmit.disabled = false;
            resultLoading.classList.add('d-none');
            resultPlaceholder.classList.remove('d-none');
            console.error('Lỗi tính phí:', err);
        });
    });
});
</script>
@endpush
