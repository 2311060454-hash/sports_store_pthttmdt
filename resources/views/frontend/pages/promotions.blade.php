@extends('layouts.app')

@section('title', 'Khuyến Mãi & Mã Giảm Giá - Sport Store')

@section('content')
<!-- Page Header Banner -->
<div class="py-5 mb-5 text-white position-relative" style="background: linear-gradient(135deg, #ff5722 0%, #ff8a65 100%);">
    <div class="container text-center py-4">
        <span class="badge bg-white text-danger px-3 py-2 fw-bold text-uppercase mb-3 shadow-sm">
            <i class="fa-solid fa-fire me-1"></i> Ưu Đãi Độc Quyền
        </span>
        <h1 class="display-4 fw-extrabold mb-3">Săn Voucher Giảm Giá & Flash Sale</h1>
        <p class="lead opacity-90 max-w-700 mx-auto" style="max-width: 700px;">
            Lưu ngay mã giảm giá và khám phá hàng loạt sản phẩm thể thao chính hãng đang được giảm giá cực sâu hôm nay!
        </p>
    </div>
</div>

<div class="container py-2 mb-5">
    <!-- Section 1: Active Coupons -->
    <div class="mb-5">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h3 class="fw-extrabold mb-1"><i class="fa-solid fa-ticket text-danger me-2"></i> Mã Giảm Giá Đang Hoạt Động</h3>
                <p class="text-muted small mb-0">Áp dụng trực tiếp tại bước giỏ hàng hoặc thanh toán đơn hàng</p>
            </div>
        </div>

        <div class="row g-4">
            @forelse($coupons as $coupon)
                <div class="col-md-6 col-lg-4">
                    <div class="card border-0 shadow-sm rounded-4 h-100 position-relative overflow-hidden" style="border-left: 6px solid #ff5722 !important;">
                        <div class="card-body p-4 d-flex flex-column justify-content-between">
                            <div>
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="badge bg-danger-subtle text-danger px-3 py-2 fw-bold fs-6">
                                        @if($coupon->type === 'percent')
                                            GIẢM {{ $coupon->value }}%
                                        @else
                                            GIẢM {{ number_format($coupon->value, 0, ',', '.') }} ₫
                                        @endif
                                    </span>
                                    <small class="text-muted"><i class="fa-regular fa-clock me-1"></i> HSD: {{ $coupon->end_date->format('d/m/Y') }}</small>
                                </div>

                                <div class="font-monospace fw-extrabold fs-4 text-dark mb-1">
                                    {{ $coupon->code }}
                                </div>

                                <p class="text-secondary small mb-3">
                                    Đơn tối thiểu: <strong>{{ number_format($coupon->min_order_amount, 0, ',', '.') }} ₫</strong>
                                    @if($coupon->max_discount)
                                        (Tối đa: {{ number_format($coupon->max_discount, 0, ',', '.') }} ₫)
                                    @endif
                                </p>
                            </div>

                            <div class="d-flex align-items-center justify-content-between border-top pt-3 mt-2">
                                <small class="text-muted">Còn lại: <strong>{{ $coupon->quantity - $coupon->used }}</strong> lượt</small>
                                <button class="btn btn-outline-danger btn-sm rounded-pill px-3 fw-bold js-copy-coupon" data-code="{{ $coupon->code }}">
                                    <i class="fa-regular fa-copy me-1"></i> Sao chép mã
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-light text-center py-4 border rounded-3">
                        Hiện chưa có mã giảm giá mới. Bạn hãy quay lại sau nhé!
                    </div>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Section 2: Hot Discounted Products (Flash Sale) -->
    <div>
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h3 class="fw-extrabold mb-1"><i class="fa-solid fa-bolt text-warning me-2"></i> Sản Phẩm Đang Giảm Giá Sâu</h3>
                <p class="text-muted small mb-0">Tiết kiệm lên đến 40% cho các sản phẩm chính hãng</p>
            </div>
            <a href="{{ route('products.index', ['on_sale' => 1]) }}" class="btn btn-outline-primary rounded-pill px-4 fw-semibold btn-sm">
                Xem tất cả <i class="fa-solid fa-arrow-right ms-1"></i>
            </a>
        </div>

        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
            @foreach($discountedProducts as $product)
                <div class="col">
                    @include('frontend.components.product-card', ['product' => $product])
                </div>
            @endforeach
        </div>

        <div class="d-flex justify-content-center mt-5">
            {{ $discountedProducts->links() }}
        </div>
    </div>
</div>

@push('scripts')
<script>
document.querySelectorAll('.js-copy-coupon').forEach(btn => {
    btn.addEventListener('click', function() {
        const code = this.getAttribute('data-code');
        navigator.clipboard.writeText(code).then(() => {
            Swal.fire({
                icon: 'success',
                title: 'Đã sao chép mã!',
                text: 'Mã ' + code + ' đã được lưu vào bộ nhớ tạm. Hãy dán vào ô giảm giá khi thanh toán!',
                timer: 2500,
                showConfirmButton: false,
                toast: true,
                position: 'top-end'
            });
        }).catch(err => {
            prompt("Sao chép mã giảm giá:", code);
        });
    });
});
</script>
@endpush
@endsection
