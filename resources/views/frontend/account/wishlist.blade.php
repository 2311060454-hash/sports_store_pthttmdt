@extends('frontend.account.layout')

@section('title', 'Sản Phẩm Yêu Thích - Sport Store')

@section('account_breadcrumb')
    <li class="breadcrumb-item active text-primary fw-semibold" aria-current="page">Sản phẩm yêu thích</li>
@endsection

@section('account_content')
<div class="card border-0 shadow-sm rounded-4 p-4">
    <h4 class="fw-bold mb-4">Danh Sách Sản Phẩm Yêu Thích ({{ $wishlists->total() }})</h4>

    @if($wishlists->count() > 0)
        <div class="row g-4">
            @foreach($wishlists as $item)
                @if($item->product)
                    <div class="col-6 col-md-4">
                        @include('frontend.components.product-card', ['product' => $item->product])
                    </div>
                @endif
            @endforeach
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $wishlists->links('pagination::bootstrap-5') }}
        </div>
    @else
        <div class="text-center py-5 text-muted">
            <i class="fa-solid fa-heart-crack fs-1 mb-2 d-block text-secondary"></i>
            Bạn chưa lưu sản phẩm nào vào danh sách yêu thích.
            <div class="mt-3">
                <a href="{{ route('products.index') }}" class="btn btn-outline-primary rounded-pill px-4">
                    Xem sản phẩm ngay
                </a>
            </div>
        </div>
    @endif
</div>
@endsection
