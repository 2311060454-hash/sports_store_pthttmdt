@extends('layouts.admin')

@section('title', 'Quản Lý Giao Hàng Nhanh (GHN) - Sport Store Admin')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h4 class="fw-bold mb-1"><i class="fa-solid fa-truck-fast text-primary me-2"></i> Đồng Bộ Giao Hàng Nhanh (GHN)</h4>
        <p class="text-muted small mb-0">Quản lý kết nối API, cước phí và danh sách vận đơn đồng bộ lên GHN Sandbox</p>
    </div>
    <div class="d-flex gap-2">
        <a href="https://5sao.ghn.dev/" target="_blank" class="btn btn-outline-primary rounded-pill px-3 shadow-sm">
            <i class="fa-solid fa-arrow-up-right-from-square me-1"></i> Mở Trang GHN (5sao.ghn.dev)
        </a>
        <button type="button" id="btnTestConnection" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm">
            <i class="fa-solid fa-plug me-1"></i> Kiểm Tra Kết Nối GHN
        </button>
    </div>
</div>

<!-- Alert Connection Result -->
<div id="testResultAlert" class="alert d-none rounded-3 mb-4"></div>

<div class="row g-4 mb-4">
    <!-- Configuration Form -->
    <div class="col-12">
        <div class="card border-0 shadow-sm rounded-4 p-4">
            <h5 class="fw-bold mb-3 border-bottom pb-2">
                <i class="fa-solid fa-gears text-primary me-2"></i> Cấu Hình Thông Số GHN API
            </h5>
            
            <form action="{{ route('admin.ghn.settings') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-semibold">GHN Base API URL</label>
                    <input type="text" class="form-control bg-light font-monospace" value="{{ $config['base_url'] }}" readonly disabled>
                    <small class="text-muted">Môi trường thử nghiệm: <code>https://dev-online-gateway.ghn.vn/shiip/public-api</code></small>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">GHN API Token <span class="text-danger">*</span></label>
                    <input type="text" name="ghn_token" id="ghn_token_input" class="form-control font-monospace" 
                           placeholder="Dán mã Token API lấy từ 5sao.ghn.dev..." value="{{ old('ghn_token', $config['token']) }}" required>
                    <small class="text-muted">Mã Token bí mật do GHN cấp trong mục <em>Quản lý thông tin &gt; Token API</em>.</small>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Shop ID <span class="text-danger">*</span></label>
                        <input type="number" name="ghn_shop_id" id="ghn_shop_id_input" class="form-control font-monospace" 
                               placeholder="Ví dụ: 216452" value="{{ old('ghn_shop_id', $config['shop_id']) }}" required>
                        <small class="text-muted">Mã số cửa hàng của bạn trên GHN.</small>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Mã Quận Kho Gửi (From District) <span class="text-danger">*</span></label>
                        <input type="number" name="ghn_from_district_id" class="form-control font-monospace" 
                               value="{{ old('ghn_from_district_id', $config['from_district_id']) }}" required>
                        <small class="text-muted">Mặc định: <code>1450</code> (Q. Thanh Xuân) hoặc <code>1442</code> (Q. Cầu Giấy).</small>
                    </div>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold">
                        <i class="fa-solid fa-floppy-disk me-1"></i> Lưu Cấu Hình GHN
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Quick Shipping Rate Checker Tool -->
<div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-3 border-bottom pb-2">
        <h5 class="fw-bold mb-0">
            <i class="fa-solid fa-calculator text-primary me-2"></i> Công Cụ Tính Cước Phí Nhanh (Tra Cứu Báo Giá GHN)
        </h5>
        <span class="badge bg-primary-subtle text-primary px-3 py-2 small">
            Kho xuất phát: Sport Store Cầu Giấy (Quận 1442 - Phường 20508)
        </span>
    </div>

    <form id="adminQuickCalcFrm" class="row g-3 align-items-end">
        <div class="col-md-3">
            <label class="form-label small fw-semibold">Tỉnh / Thành phố đích</label>
            <select id="adm_calc_province" class="form-select form-select-sm rounded-3">
                <option value="">-- Đang tải Tỉnh/Thành... --</option>
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label small fw-semibold">Quận / Huyện đích</label>
            <select id="adm_calc_district" class="form-select form-select-sm rounded-3" disabled>
                <option value="">-- Chọn Quận/Huyện --</option>
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label small fw-semibold">Phường / Xã đích</label>
            <select id="adm_calc_ward" class="form-select form-select-sm rounded-3" disabled>
                <option value="">-- Chọn Phường/Xã --</option>
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label small fw-semibold">Trọng lượng (Gram)</label>
            <div class="input-group input-group-sm">
                <input type="number" id="adm_calc_weight" class="form-control" value="500" min="50" max="50000">
                <span class="input-group-text">g</span>
            </div>
        </div>

        <div class="col-md-4">
            <label class="form-label small fw-semibold">Giá trị đơn hàng (Bảo hiểm)</label>
            <div class="input-group input-group-sm">
                <input type="number" id="adm_calc_insurance" class="form-control" value="500000" min="0" step="50000">
                <span class="input-group-text">₫</span>
            </div>
        </div>

        <div class="col-md-5">
            <label class="form-label small text-muted">Chọn nhanh gói hàng:</label>
            <div class="d-flex flex-wrap gap-1">
                <button type="button" class="btn btn-xs btn-outline-secondary py-1 px-2 small js-adm-preset" data-w="300" style="font-size: 0.75rem;">Áo/Quần (300g)</button>
                <button type="button" class="btn btn-xs btn-outline-secondary py-1 px-2 small js-adm-preset" data-w="500" style="font-size: 0.75rem;">Vợt (500g)</button>
                <button type="button" class="btn btn-xs btn-outline-secondary py-1 px-2 small js-adm-preset" data-w="900" style="font-size: 0.75rem;">Giày (900g)</button>
                <button type="button" class="btn btn-xs btn-outline-secondary py-1 px-2 small js-adm-preset" data-w="600" style="font-size: 0.75rem;">Bóng (600g)</button>
            </div>
        </div>

        <div class="col-md-3 text-end">
            <button type="button" id="btnAdminCalculateFee" class="btn btn-primary btn-sm rounded-pill px-4 fw-bold w-100 py-2">
                <i class="fa-solid fa-calculator me-1"></i> Tính Cước Ngay
            </button>
        </div>
    </form>

    <!-- Quick Result Alert -->
    <div id="admCalcResult" class="d-none mt-3 p-3 rounded-3 bg-light border border-primary-subtle">
        <div class="row align-items-center">
            <div class="col-md-4">
                <span class="small text-muted d-block">Tổng cước GHN:</span>
                <span class="fs-4 fw-extrabold text-primary" id="admCalcTotalFee">0 ₫</span>
            </div>
            <div class="col-md-4">
                <span class="small text-muted d-block">Dự kiến giao:</span>
                <span class="fw-bold text-dark" id="admCalcDeliveryTime">1 - 2 ngày</span>
            </div>
            <div class="col-md-4 text-md-end">
                <a href="{{ route('shipping.calculator') }}" target="_blank" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                    Mở trang tra cứu công khai <i class="fa-solid fa-arrow-up-right-from-square ms-1"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- List of Synced GHN Orders -->
<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    <div class="card-header bg-white py-3 px-4 d-flex justify-content-between align-items-center">
        <h5 class="fw-bold mb-0"><i class="fa-solid fa-boxes-packing text-primary me-2"></i> Danh Sách Vận Đơn Giao Hàng Nhanh</h5>
        <span class="badge bg-secondary-subtle text-secondary px-3 py-2 small">
            Tổng cộng: {{ $ghnOrders->total() }} vận đơn
        </span>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th class="py-3 px-4">Mã Đơn / Khách Hàng</th>
                    <th class="py-3">Mã Vận Đơn GHN</th>
                    <th class="py-3">Cước Phí GHN</th>
                    <th class="py-3">Dự Kiến Giao</th>
                    <th class="py-3">Trạng Thái Vận Chuyển</th>
                    <th class="py-3 text-end px-4">Thao Tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($ghnOrders as $order)
                    <tr>
                        <td class="px-4">
                            <a href="{{ route('admin.orders.show', $order->id) }}" class="fw-bold text-decoration-none text-primary">
                                #{{ $order->order_code }}
                            </a>
                            <div class="small text-muted">{{ $order->customer_name }} • {{ $order->customer_phone }}</div>
                        </td>
                        <td>
                            <span class="badge bg-dark font-monospace fs-6 px-3 py-2">
                                <i class="fa-solid fa-barcode me-1"></i> {{ $order->ghn_order_code }}
                            </span>
                        </td>
                        <td class="fw-semibold">
                            {{ number_format($order->ghn_fee ?: $order->ghn_total_fee, 0, ',', '.') }} ₫
                        </td>
                        <td>
                            <small class="text-muted">
                                <i class="fa-regular fa-calendar-check me-1"></i>
                                {{ $order->ghn_expected_delivery ? $order->ghn_expected_delivery->format('d/m/Y H:i') : 'Đang cập nhật' }}
                            </small>
                        </td>
                        <td>
                            <span class="badge {{ $order->ghn_status_badge }} px-3 py-2">
                                {{ $order->ghn_status_label }}
                            </span>
                        </td>
                        <td class="text-end px-4">
                            <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                                Chi tiết <i class="fa-solid fa-angle-right ms-1"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="fa-solid fa-truck-ramp-box fs-1 mb-2"></i>
                            <p class="mb-0">Chưa có vận đơn nào được đồng bộ sang GHN.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($ghnOrders->hasPages())
        <div class="card-footer bg-white py-3 px-4 border-0">
            {{ $ghnOrders->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const btnTest = document.getElementById('btnTestConnection');
    const resultAlert = document.getElementById('testResultAlert');

    btnTest.addEventListener('click', function () {
        btnTest.disabled = true;
        btnTest.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Đang kết nối GHN...';
        resultAlert.classList.add('d-none');

        fetch("{{ route('admin.ghn.test') }}")
            .then(res => res.json())
            .then(data => {
                btnTest.disabled = false;
                btnTest.innerHTML = '<i class="fa-solid fa-plug me-1"></i> Kiểm Tra Kết Nối GHN';
                resultAlert.classList.remove('d-none', 'alert-success', 'alert-danger');

                if (data.success) {
                    resultAlert.classList.add('alert-success');
                    resultAlert.innerHTML = `<i class="fa-solid fa-circle-check me-2"></i> <strong>Thành công!</strong> ${data.message}`;
                } else {
                    resultAlert.classList.add('alert-danger');
                    resultAlert.innerHTML = `<i class="fa-solid fa-triangle-exclamation me-2"></i> <strong>Chưa kết nối được:</strong> ${data.message}`;
                }
            })
            .catch(err => {
                btnTest.disabled = false;
                btnTest.innerHTML = '<i class="fa-solid fa-plug me-1"></i> Kiểm Tra Kết Nối GHN';
                resultAlert.classList.remove('d-none', 'alert-success');
                resultAlert.classList.add('alert-danger');
                resultAlert.innerHTML = `<i class="fa-solid fa-triangle-exclamation me-2"></i> Lỗi kết nối mạng: ${err.message}`;
            });
    // --- Admin Quick Rate Checker ---
    const admProvince = document.getElementById('adm_calc_province');
    const admDistrict = document.getElementById('adm_calc_district');
    const admWard = document.getElementById('adm_calc_ward');
    const admWeight = document.getElementById('adm_calc_weight');
    const admInsurance = document.getElementById('adm_calc_insurance');
    const btnAdminCalc = document.getElementById('btnAdminCalculateFee');
    const admCalcResult = document.getElementById('admCalcResult');
    const admCalcTotalFee = document.getElementById('admCalcTotalFee');
    const admCalcDeliveryTime = document.getElementById('admCalcDeliveryTime');

    // 1. Tải tỉnh thành cho Admin
    fetch("{{ route('locations.provinces') }}")
        .then(res => res.json())
        .then(res => {
            if (res.data) {
                let options = '<option value="">-- Chọn Tỉnh / Thành --</option>';
                res.data.forEach(p => {
                    options += `<option value="${p.ProvinceID}">${p.ProvinceName}</option>`;
                });
                admProvince.innerHTML = options;
            }
        })
        .catch(err => console.error("Admin load provinces error:", err));

    // 2. Chọn Tỉnh -> Tải Huyện
    admProvince.addEventListener('change', function () {
        admDistrict.innerHTML = '<option value="">-- Đang tải... --</option>';
        admDistrict.disabled = true;
        admWard.innerHTML = '<option value="">-- Chọn Phường/Xã --</option>';
        admWard.disabled = true;
        admCalcResult.classList.add('d-none');

        if (!this.value) return;

        fetch(`/locations/districts/${this.value}`)
            .then(res => res.json())
            .then(res => {
                if (res.data) {
                    let options = '<option value="">-- Chọn Quận / Huyện --</option>';
                    res.data.forEach(d => {
                        options += `<option value="${d.DistrictID}">${d.DistrictName}</option>`;
                    });
                    admDistrict.innerHTML = options;
                    admDistrict.disabled = false;
                }
            });
    });

    // 3. Chọn Huyện -> Tải Xã
    admDistrict.addEventListener('change', function () {
        admWard.innerHTML = '<option value="">-- Đang tải... --</option>';
        admWard.disabled = true;
        admCalcResult.classList.add('d-none');

        if (!this.value) return;

        fetch(`/locations/wards/${this.value}`)
            .then(res => res.json())
            .then(res => {
                if (res.data) {
                    let options = '<option value="">-- Chọn Phường / Xã --</option>';
                    res.data.forEach(w => {
                        options += `<option value="${w.WardCode}">${w.WardName}</option>`;
                    });
                    admWard.innerHTML = options;
                    admWard.disabled = false;
                }
            });
    });

    // 4. Preset weight buttons
    document.querySelectorAll('.js-adm-preset').forEach(btn => {
        btn.addEventListener('click', function () {
            admWeight.value = this.dataset.w;
        });
    });

    // 5. Tính cước
    btnAdminCalc.addEventListener('click', function () {
        if (!admDistrict.value || !admWard.value) {
            alert('Vui lòng chọn đầy đủ Tỉnh/Thành, Quận/Huyện và Phường/Xã đích!');
            return;
        }

        btnAdminCalc.disabled = true;
        btnAdminCalc.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Đang tính...';

        fetch("{{ route('locations.custom-fee') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                province_id: admProvince.value,
                to_district_id: admDistrict.value,
                to_ward_code: admWard.value,
                weight: admWeight.value,
                insurance_value: admInsurance.value
            })
        })
        .then(res => res.json())
        .then(res => {
            btnAdminCalc.disabled = false;
            btnAdminCalc.innerHTML = '<i class="fa-solid fa-calculator me-1"></i> Tính Cước Ngay';

            let fee = 30000;
            if (res.ghn_response && res.ghn_response.data && res.ghn_response.data.total) {
                fee = res.ghn_response.data.total;
            }

            admCalcTotalFee.innerText = new Intl.NumberFormat('vi-VN').format(fee) + ' ₫';
            admCalcDeliveryTime.innerText = (res.expected_time || '1 - 2 ngày') + ' (Giao trước: ' + (res.expected_date || '') + ')';
            admCalcResult.classList.remove('d-none');
        })
        .catch(err => {
            btnAdminCalc.disabled = false;
            btnAdminCalc.innerHTML = '<i class="fa-solid fa-calculator me-1"></i> Tính Cước Ngay';
            alert('Lỗi tính cước: ' + err.message);
        });
    });
});
</script>
@endpush
