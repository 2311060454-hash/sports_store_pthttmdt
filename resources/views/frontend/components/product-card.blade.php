<div class="product-card">
    @if($product->discount_percent > 0)
        <div class="product-badge-discount">-{{ $product->discount_percent }}%</div>
    @endif

    <button type="button" 
            class="btn-wishlist-toggle js-btn-wishlist {{ auth()->check() && auth()->user()->wishlists()->where('product_id', $product->id)->exists() ? 'active' : '' }}" 
            data-product-id="{{ $product->id }}" 
            title="Thêm vào yêu thích">
        <i class="fa-{{ auth()->check() && auth()->user()->wishlists()->where('product_id', $product->id)->exists() ? 'solid' : 'regular' }} fa-heart {{ auth()->check() && auth()->user()->wishlists()->where('product_id', $product->id)->exists() ? 'text-danger' : '' }}"></i>
    </button>

    <div class="img-wrapper">
        <a href="{{ route('products.show', $product->slug) }}">
            <img src="{{ asset($product->thumbnail ?? 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=600&auto=format&fit=crop&q=60') }}" 
                 alt="{{ $product->name }}" 
                 loading="lazy">
        </a>
    </div>

    <div class="p-3 d-flex flex-column flex-grow-1 justify-content-between">
        <div>
            <div class="d-flex justify-content-between align-items-center mb-1">
                <span class="text-uppercase text-muted" style="font-size: 0.72rem; font-weight: 700;">
                    {{ $product->category->name ?? 'Dụng cụ' }}
                </span>
                <span class="text-secondary small fw-semibold" style="font-size: 0.75rem;">
                    {{ $product->brand->name ?? '' }}
                </span>
            </div>

            <h6 class="fw-bold mb-2">
                <a href="{{ route('products.show', $product->slug) }}" class="text-dark text-decoration-none text-truncate d-block" title="{{ $product->name }}">
                    {{ $product->name }}
                </a>
            </h6>

            <div class="d-flex align-items-center mb-2 small">
                <div class="text-warning me-1">
                    @for($i = 1; $i <= 5; $i++)
                        @if($i <= round($product->average_rating))
                            <i class="fa-solid fa-star" style="font-size: 0.75rem;"></i>
                        @else
                            <i class="fa-regular fa-star" style="font-size: 0.75rem;"></i>
                        @endif
                    @endfor
                </div>
                <span class="text-muted" style="font-size: 0.75rem;">({{ $product->reviews->count() }})</span>
                <span class="mx-1 text-muted">•</span>
                <span class="text-muted" style="font-size: 0.75rem;">Đã bán {{ $product->sold }}</span>
            </div>
        </div>

        <div>
            <div class="d-flex align-items-baseline mb-3">
                <span class="price-current">{{ number_format($product->final_price, 0, ',', '.') }} ₫</span>
                @if($product->sale_price && $product->sale_price < $product->price)
                    <span class="price-old">{{ number_format($product->price, 0, ',', '.') }} ₫</span>
                @endif
            </div>

            @if($product->quantity > 0)
                <button type="button" 
                        class="btn btn-primary w-100 btn-sm rounded-pill d-flex align-items-center justify-content-center js-quick-add-cart" 
                        data-product-id="{{ $product->id }}">
                    <i class="fa-solid fa-cart-plus me-2"></i> Thêm vào giỏ
                </button>
            @else
                <button type="button" class="btn btn-secondary w-100 btn-sm rounded-pill disabled" disabled>
                    <i class="fa-solid fa-ban me-1"></i> Tạm hết hàng
                </button>
            @endif
        </div>
    </div>
</div>
