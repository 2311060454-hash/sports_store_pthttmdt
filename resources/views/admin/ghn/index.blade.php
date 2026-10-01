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
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm rounded-4 p-4 h-100">
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

    <!-- Instruction Guide -->
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm rounded-4 p-4 h-100 bg-primary bg-opacity-10 border-start border-4 border-primary">
            <h5 class="fw-bold mb-3 text-primary">
                <i class="fa-solid fa-circle-question me-2"></i> Cách để đơn hàng xuất hiện bên GHN
            </h5>
            <ol class="small text-secondary mb-3 ps-3 leading-relaxed">
                <li class="mb-2">
                    Truy cập trang web thử nghiệm của GHN: 
                    <a href="https://5sao.ghn.dev/" target="_blank" class="fw-bold text-decoration-none">https://5sao.ghn.dev/</a>
                    và đăng nhập tài khoản (hoặc đăng ký bằng SĐT miễn phí).
                </li>
                <li class="mb-2">
                    Tại góc trên bên phải màn hình GHN, xem <strong>Mã Shop ID</strong> (ví dụ: <code>216452</code>).
                </li>
                <li class="mb-2">
                    Nhấp vào tên tài khoản &gt; chọn <strong>Quản lý thông tin</strong> &gt; mục <strong>Token API</strong> &gt; Copy chuỗi Token dạng <code>c793a1aa-...</code>.
                </li>
                <li class="mb-2">
                    Dán <strong>Token</strong> và <strong>Shop ID</strong> vào biểu mẫu bên trái rồi nhấn <strong>Lưu Cấu Hình</strong>.
                </li>
                <li>
                    Khi khách đặt hàng trên web hoặc Admin bấm <strong>"Tạo vận đơn GHN"</strong>, hệ thống tự động đẩy gói hàng sang GHN. Mở lại trang <strong>5sao.ghn.dev &gt; Quản lý đơn hàng &gt; Chờ bàn giao</strong> sẽ thấy đơn hàng xuất hiện ngay!
                </li>
            </ol>
            <div class="p-3 bg-white rounded-3 border">
                <div class="d-flex align-items-center gap-2 text-dark fw-semibold small">
                    <i class="fa-solid fa-circle-check text-success"></i> Hệ thống đã tích hợp cơ chế tự động đồng bộ khi khách hoàn tất Checkout!
                </div>
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
    });
});
</script>
@endpush
