@extends('layouts.app')

@section('title', $product->name . ' - Sport Store')
@section('meta_description', Str::limit(strip_tags($product->description ?? $product->name), 160))
@section('og_title', $product->name . ' - Sport Store')
@section('og_description', Str::limit(strip_tags($product->description ?? $product->name), 160))
@section('og_image', asset($product->thumbnail))
@section('og_type', 'product')

@section('content')
<div class="container py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-muted">Trang chủ</a></li>
            <li class="breadcrumb-item"><a href="{{ route('products.index') }}" class="text-decoration-none text-muted">Sản phẩm</a></li>
            <li class="breadcrumb-item"><a href="{{ route('products.index', ['category' => $product->category->slug]) }}" class="text-decoration-none text-muted">{{ $product->category->name }}</a></li>
            <li class="breadcrumb-item active text-dark fw-semibold" aria-current="page">{{ Str::limit($product->name, 35) }}</li>
        </ol>
    </nav>

    <!-- Product Summary Section -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-5">
        <div class="card-body p-4 p-lg-5">
            <div class="row g-4 g-lg-5">
                <!-- Gallery & Images Column -->
                <div class="col-lg-6">
                    <!-- Main Image Display -->
                    <div class="position-relative rounded-4 overflow-hidden bg-light mb-3 text-center" style="height: 440px; border: 1px solid var(--border-color);">
                        @if($product->discount_percent > 0)
                            <div class="product-badge-discount fs-6">-{{ $product->discount_percent }}%</div>
                        @endif
                        <img id="mainProductImage" 
                             src="{{ asset($product->thumbnail ?? 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=800&auto=format&fit=crop&q=80') }}" 
                             alt="{{ $product->name }}" 
                             class="w-100 h-100" 
                             style="object-fit: contain;">
                    </div>

                    <!-- Thumbnails row -->
                    <div class="d-flex gap-2 overflow-x-auto pb-2">
                        <div class="thumb-box border border-primary rounded-3 overflow-hidden cursor-pointer" style="width: 75px; height: 75px; flex-shrink: 0;" onclick="changeMainImage('{{ asset($product->thumbnail) }}', this)">
                            <img src="{{ asset($product->thumbnail) }}" class="w-100 h-100" style="object-fit: cover;">
                        </div>
                        @foreach($product->images as $img)
                            <div class="thumb-box border rounded-3 overflow-hidden cursor-pointer" style="width: 75px; height: 75px; flex-shrink: 0;" onclick="changeMainImage('{{ asset($img->image) }}', this)">
                                <img src="{{ asset($img->image) }}" class="w-100 h-100" style="object-fit: cover;">
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Product Details Column -->
                <div class="col-lg-6">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <span class="badge bg-primary-subtle text-primary fw-bold text-uppercase mb-2">{{ $product->category->name }}</span>
                            <span class="badge bg-secondary-subtle text-secondary fw-bold text-uppercase mb-2 ms-1">{{ $product->brand->name }}</span>
                        </div>
                        <span class="text-muted small">Mã: <strong>{{ $product->sku }}</strong></span>
                    </div>

                    <h2 class="fw-bold mb-3">{{ $product->name }}</h2>

                    <!-- Ratings and Sold count -->
                    <div class="d-flex align-items-center mb-3">
                        <div class="text-warning me-2">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= round($product->average_rating))
                                    <i class="fa-solid fa-star"></i>
                                @else
                                    <i class="fa-regular fa-star"></i>
                                @endif
                            @endfor
                        </div>
                        <span class="fw-bold me-2">{{ number_format($product->average_rating, 1) }}</span>
                        <a href="#reviews-section" class="text-muted text-decoration-none small me-3">({{ $product->approvedReviews->count() }} đánh giá)</a>
                        <span class="text-muted small">|</span>
                        <span class="text-muted small ms-3">Đã bán: <strong>{{ $product->sold }}</strong> sản phẩm</span>
                    </div>

                    <!-- Price Block -->
                    <div class="p-3 bg-light rounded-3 mb-4 d-flex align-items-baseline gap-3">
                        <span class="fs-2 fw-extrabold text-primary" id="displayPrice">{{ number_format($product->final_price, 0, ',', '.') }} ₫</span>
                        @if($product->sale_price && $product->sale_price < $product->price)
                            <span class="fs-5 text-muted text-decoration-line-through">{{ number_format($product->price, 0, ',', '.') }} ₫</span>
                        @endif
                    </div>

                    <!-- Short Description -->
                    @if($product->short_description)
                        <p class="text-muted mb-4">{{ $product->short_description }}</p>
                    @endif

                    <!-- Stock Status -->
                    <div class="mb-4">
                        @if($product->quantity > 0)
                            <span class="badge bg-success-subtle text-success px-3 py-2 fs-6">
                                <i class="fa-solid fa-check-circle me-1"></i> Còn hàng ({{ $product->quantity }} sản phẩm)
                            </span>
                        @else
                            <span class="badge bg-danger-subtle text-danger px-3 py-2 fs-6">
                                <i class="fa-solid fa-circle-xmark me-1"></i> Hết hàng
                            </span>
                        @endif
                    </div>

                    <!-- Add to cart Form -->
                    <form action="{{ route('cart.add') }}" method="POST">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">

                        <!-- Variants: Sizes & Colors -->
                        @if($product->variants->count() > 0)
                            <div class="mb-4">
                                <label class="form-label fw-bold mb-2">Chọn phiên bản / kích cỡ:</label>
                                <div class="d-flex flex-wrap gap-2">
                                    @foreach($product->variants as $index => $var)
                                        <input type="radio" 
                                               class="btn-check js-variant-radio" 
                                               name="variant_id" 
                                               id="var_{{ $var->id }}" 
                                               value="{{ $var->id }}" 
                                               data-price-diff="{{ $var->additional_price }}" 
                                               data-stock="{{ $var->quantity }}"
                                               {{ $index === 0 ? 'checked' : '' }}
                                               {{ $var->quantity <= 0 ? 'disabled' : '' }}>
                                        <label class="btn btn-outline-secondary {{ $var->quantity <= 0 ? 'opacity-50 text-decoration-line-through' : '' }}" for="var_{{ $var->id }}">
                                            @if($var->size) Size: {{ $var->size }} @endif
                                            @if($var->color) - Màu: {{ $var->color }} @endif
                                            @if($var->additional_price > 0) (+{{ number_format($var->additional_price, 0, ',', '.') }}₫) @endif
                                            @if($var->quantity <= 0) (Hết) @endif
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Quantity Selector -->
                        @if($product->quantity > 0)
                            <div class="d-flex align-items-center mb-4 gap-3">
                                <label class="form-label fw-bold mb-0">Số lượng:</label>
                                <div class="input-group" style="width: 140px;">
                                    <button class="btn btn-outline-secondary" type="button" onclick="decrementQty()"><i class="fa-solid fa-minus"></i></button>
                                    <input type="number" id="qtyInput" name="quantity" class="form-control text-center font-monospace fw-bold" value="1" min="1" max="{{ $product->quantity }}">
                                    <button class="btn btn-outline-secondary" type="button" onclick="incrementQty({{ $product->quantity }})"><i class="fa-solid fa-plus"></i></button>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="d-flex flex-wrap gap-3">
                                <button type="submit" class="btn btn-outline-primary btn-lg rounded-pill px-4 fw-bold flex-grow-1">
                                    <i class="fa-solid fa-cart-plus me-2"></i> Thêm vào giỏ hàng
                                </button>
                                <button type="submit" name="buy_now" value="1" class="btn btn-primary btn-lg rounded-pill px-4 fw-bold flex-grow-1 shadow-sm">
                                    <i class="fa-solid fa-bolt me-2"></i> Mua ngay
                                </button>
                                <button type="button" 
                                        class="btn btn-outline-danger btn-lg rounded-circle js-btn-wishlist {{ auth()->check() && auth()->user()->wishlists()->where('product_id', $product->id)->exists() ? 'active' : '' }}" 
                                        data-product-id="{{ $product->id }}" 
                                        title="Lưu yêu thích" 
                                        style="width: 50px; height: 50px;">
                                    <i class="fa-solid fa-heart"></i>
                                </button>
                            </div>
                        @else
                            <div class="alert alert-danger mb-0">
                                <i class="fa-solid fa-circle-exclamation me-2"></i> Sản phẩm hiện đang tạm hết hàng. Quý khách vui lòng quay lại sau hoặc liên hệ hotline để nhận thông báo hàng về!
                            </div>
                        @endif
                    </form>

                    <!-- Social Sharing (Yêu cầu Mạng xã hội / Chia sẻ sản phẩm) -->
                    <div class="d-flex align-items-center gap-2 mt-4 pt-3 border-top">
                        <span class="small text-muted fw-semibold me-2"><i class="fa-solid fa-share-nodes me-1"></i> Chia sẻ:</span>
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" target="_blank" class="btn btn-sm btn-outline-primary rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 34px; height: 34px;" title="Chia sẻ lên Facebook">
                            <i class="fa-brands fa-facebook-f"></i>
                        </a>
                        <a href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}&text={{ urlencode($product->name) }}" target="_blank" class="btn btn-sm btn-outline-dark rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 34px; height: 34px;" title="Chia sẻ lên X">
                            <i class="fa-brands fa-x-twitter"></i>
                        </a>
                        <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3" onclick="copyProductLink()" title="Sao chép liên kết">
                            <i class="fa-solid fa-link me-1"></i> Sao chép link
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Product Description & Reviews Tabs -->
    <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 mb-5" id="reviews-section">
        <ul class="nav nav-tabs border-bottom mb-4" id="productTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active fw-bold py-3 px-4 fs-6" id="desc-tab" data-bs-toggle="tab" data-bs-target="#desc" type="button">
                    <i class="fa-solid fa-circle-info me-2 text-primary"></i> Mô Tả Chi Tiết
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link fw-bold py-3 px-4 fs-6" id="reviews-tab" data-bs-toggle="tab" data-bs-target="#reviews" type="button">
                    <i class="fa-solid fa-star me-2 text-warning"></i> Đánh Giá ({{ $product->approvedReviews->count() }})
                </button>
            </li>
        </ul>

        <div class="tab-content" id="productTabContent">
            <!-- Description Tab -->
            <div class="tab-pane fade show active" id="desc" role="tabpanel">
                <div class="lh-lg text-secondary">
                    {!! nl2br(e($product->description)) !!}
                </div>
            </div>

            <!-- Reviews Tab -->
            <div class="tab-pane fade" id="reviews" role="tabpanel">
                <div class="row g-4">
                    <div class="col-lg-7">
                        <h5 class="fw-bold mb-4">Nhận xét từ khách hàng đã mua</h5>

                        @forelse($product->approvedReviews as $review)
                            <div class="card border-0 bg-light rounded-3 p-3 mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle bg-warning-subtle text-warning fw-bold d-flex align-items-center justify-content-center me-2" style="width: 38px; height: 38px;">
                                            {{ strtoupper(substr($review->user->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark">{{ $review->user->name }}</div>
                                            <small class="text-muted">{{ $review->created_at->format('d/m/Y H:i') }}</small>
                                        </div>
                                    </div>
                                    <div class="text-warning">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fa-{{ $i <= $review->rating ? 'solid' : 'regular' }} fa-star"></i>
                                        @endfor
                                    </div>
                                </div>
                                <p class="mb-0 text-secondary">{{ $review->comment }}</p>
                            </div>
                        @empty
                            <p class="text-muted">Chưa có đánh giá nào cho sản phẩm này. Hãy là người đầu tiên trải nghiệm và để lại nhận xét!</p>
                        @endforelse
                    </div>

                    <!-- Review Submission Form (Only if eligible) -->
                    <div class="col-lg-5">
                        <div class="card border-0 bg-light-subtle shadow-sm rounded-4 p-4">
                            <h5 class="fw-bold mb-3"><i class="fa-solid fa-pen me-2 text-primary"></i> Gửi Đánh Giá Của Bạn</h5>

                            @if($canReview)
                                <form action="{{ route('reviews.store') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <input type="hidden" name="order_id" value="{{ $eligibleOrderId }}">

                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Chất lượng sản phẩm:</label>
                                        <select name="rating" class="form-select" required>
                                            <option value="5">⭐⭐⭐⭐⭐ 5 Sao - Cực kỳ hài lòng</option>
                                            <option value="4">⭐⭐⭐⭐ 4 Sao - Hài lòng</option>
                                            <option value="3">⭐⭐⭐ 3 Sao - Bình thường</option>
                                            <option value="2">⭐⭐ 2 Sao - Chưa hài lòng</option>
                                            <option value="1">⭐ 1 Sao - Rất tệ</option>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Nội dung nhận xét:</label>
                                        <textarea name="comment" class="form-control" rows="4" placeholder="Chia sẻ cảm nhận về chất lượng, đóng gói, độ bền sản phẩm..." required></textarea>
                                    </div>

                                    <button type="submit" class="btn btn-primary w-100 rounded-pill fw-bold">
                                        Gửi Đánh Giá Ngay
                                    </button>
                                </form>
                            @else
                                <div class="alert alert-info small mb-0">
                                    <i class="fa-solid fa-shield-halved me-1"></i> Theo chính sách đảm bảo tính trung thực, chỉ những khách hàng đã mua sản phẩm này và đơn hàng đã <strong>hoàn thành</strong> mới có thể gửi đánh giá.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Related Products -->
    @if($relatedProducts->count() > 0)
        <div class="mb-5">
            <h4 class="fw-extrabold mb-4">Sản Phẩm Tương Tự</h4>
            <div class="row g-4">
                @foreach($relatedProducts as $rel)
                    <div class="col-6 col-md-3">
                        @include('frontend.components.product-card', ['product' => $rel])
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Recently Viewed Products (Cá nhân hóa & Gợi ý) -->
    @if(isset($recentlyViewed) && $recentlyViewed->count() > 0)
        <div class="mb-5">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="fw-extrabold mb-0">
                    <i class="fa-solid fa-clock-rotate-left text-primary me-2"></i> Sản Phẩm Bạn Đã Xem Gần Đây
                </h4>
                <span class="text-muted small">Dựa trên lịch sử duyệt web</span>
            </div>
            <div class="row g-4">
                @foreach($recentlyViewed as $recent)
                    <div class="col-6 col-md-3">
                        @include('frontend.components.product-card', ['product' => $recent])
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    function changeMainImage(url, el) {
        document.getElementById('mainProductImage').src = url;
        document.querySelectorAll('.thumb-box').forEach(box => {
            box.classList.remove('border-primary');
        });
        el.classList.add('border-primary');
    }

    function incrementQty(max) {
        let input = document.getElementById('qtyInput');
        let current = parseInt(input.value) || 1;
        if (current < max) {
            input.value = current + 1;
        }
    }

    function decrementQty() {
        let input = document.getElementById('qtyInput');
        let current = parseInt(input.value) || 1;
        if (current > 1) {
            input.value = current - 1;
        }
    }

    function copyProductLink() {
        navigator.clipboard.writeText(window.location.href).then(() => {
            Swal.fire({
                icon: 'success',
                title: 'Đã sao chép liên kết!',
                text: 'Bạn có thể chia sẻ sản phẩm này cho bạn bè.',
                timer: 2000,
                showConfirmButton: false,
                toast: true,
                position: 'top-end'
            });
        });
    }
</script>
@endpush
