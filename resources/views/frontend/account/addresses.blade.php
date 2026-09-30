@extends('frontend.account.layout')

@section('title', 'Sổ Địa Chỉ Giao Hàng - Sport Store')

@section('account_breadcrumb')
    <li class="breadcrumb-item active text-primary fw-semibold" aria-current="page">Sổ địa chỉ</li>
@endsection

@section('account_content')
<div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">Sổ Địa Chỉ Của Bạn</h4>
        <button type="button" class="btn btn-primary rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#addAddressModal">
            <i class="fa-solid fa-plus me-1"></i> Thêm Địa Chỉ Mới
        </button>
    </div>

    @if($addresses->count() > 0)
        <div class="row g-3">
            @foreach($addresses as $addr)
                <div class="col-md-6">
                    <div class="card h-100 border {{ $addr->is_default ? 'border-primary' : '' }} rounded-3 p-3 position-relative">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="fw-bold mb-0">{{ $addr->receiver_name }}</h6>
                            @if($addr->is_default)
                                <span class="badge bg-primary">Mặc định</span>
                            @endif
                        </div>
                        <div class="text-muted small mb-1"><i class="fa-solid fa-phone me-1"></i> {{ $addr->phone }}</div>
                        <div class="text-secondary small mb-3">{{ $addr->full_address }}</div>

                        <div class="mt-auto d-flex justify-content-between align-items-center pt-2 border-top">
                            @if(!$addr->is_default)
                                <form action="{{ route('account.addresses.default', $addr->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-sm btn-link text-primary p-0 text-decoration-none">
                                        Đặt làm mặc định
                                    </button>
                                </form>
                            @else
                                <span class="small text-success"><i class="fa-solid fa-check me-1"></i> Đang mặc định</span>
                            @endif

                            <form action="{{ route('account.addresses.destroy', $addr->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc muốn xóa địa chỉ này?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-link text-danger p-0 text-decoration-none">
                                    <i class="fa-solid fa-trash me-1"></i> Xóa
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-5 text-muted">
            <i class="fa-solid fa-map-location-dot fs-1 mb-2 d-block text-secondary"></i>
            Chưa có địa chỉ nào được lưu. Hãy thêm địa chỉ để thanh toán nhanh chóng hơn!
        </div>
    @endif
</div>

<!-- Modal Add Address -->
<div class="modal fade" id="addAddressModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content rounded-4 border-0">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold">Thêm Địa Chỉ Mới</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('account.addresses.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Họ tên người nhận <span class="text-danger">*</span></label>
                        <input type="text" name="receiver_name" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Số điện thoại <span class="text-danger">*</span></label>
                        <input type="text" name="phone" class="form-control" required>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-4">
                            <label class="form-label fw-semibold">Tỉnh/Thành <span class="text-danger">*</span></label>
                            <input type="text" name="province" class="form-control" placeholder="TP.HCM" required>
                        </div>
                        <div class="col-4">
                            <label class="form-label fw-semibold">Quận/Huyện <span class="text-danger">*</span></label>
                            <input type="text" name="district" class="form-control" placeholder="Quận 1" required>
                        </div>
                        <div class="col-4">
                            <label class="form-label fw-semibold">Phường/Xã <span class="text-danger">*</span></label>
                            <input type="text" name="ward" class="form-control" placeholder="Phường Bến Nghé" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Địa chỉ cụ thể <span class="text-danger">*</span></label>
                        <input type="text" name="address" class="form-control" placeholder="Số nhà, tên đường..." required>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="is_default" value="1" id="is_default">
                        <label class="form-check-label small" for="is_default">
                            Đặt làm địa chỉ nhận hàng mặc định
                        </label>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-3" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold">Lưu Địa Chỉ</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
