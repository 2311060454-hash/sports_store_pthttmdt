@extends('layouts.app')

@section('title', $article['title'] . ' - Sport Store Blog')

@section('content')
<div class="container py-4 mb-5">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb small">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-muted text-decoration-none">Trang chủ</a></li>
            <li class="breadcrumb-item"><a href="{{ route('blog') }}" class="text-muted text-decoration-none">Tin tức & Blog</a></li>
            <li class="breadcrumb-item active text-primary fw-semibold" aria-current="page">{{ $article['title'] }}</li>
        </ol>
    </nav>

    <div class="row g-5">
        <!-- Main Article Content -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5">
                <span class="badge bg-primary-subtle text-primary fw-bold mb-3 align-self-start fs-6 px-3 py-2">
                    {{ $article['category'] }}
                </span>
                <h1 class="fw-extrabold mb-3">{{ $article['title'] }}</h1>

                <div class="d-flex align-items-center gap-3 text-muted small mb-4 pb-3 border-bottom">
                    <span><i class="fa-solid fa-user-pen text-primary me-1"></i> {{ $article['author'] }}</span>
                    <span><i class="fa-regular fa-calendar text-primary me-1"></i> {{ $article['date'] }}</span>
                    <span><i class="fa-regular fa-clock text-primary me-1"></i> 5 phút đọc</span>
                </div>

                <div class="rounded-4 overflow-hidden mb-4">
                    <img src="{{ $article['image'] }}" alt="{{ $article['title'] }}" class="img-fluid w-100 object-fit-cover" style="max-height: 420px;">
                </div>

                <div class="lead fw-normal text-secondary mb-4 p-3 bg-light rounded-3 border-start border-4 border-primary">
                    {{ $article['summary'] }}
                </div>

                <div class="article-body lh-lg text-secondary fs-6">
                    {!! $article['content'] !!}
                </div>

                <!-- Social Share Bar -->
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mt-5 pt-4 border-top">
                    <div class="d-flex align-items-center gap-2">
                        <span class="fw-bold text-dark me-2"><i class="fa-solid fa-share-nodes text-primary me-1"></i> Chia sẻ bài viết:</span>
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" target="_blank" class="btn btn-outline-primary btn-sm rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                            <i class="fa-brands fa-facebook-f"></i>
                        </a>
                        <a href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}&text={{ urlencode($article['title']) }}" target="_blank" class="btn btn-outline-dark btn-sm rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                            <i class="fa-brands fa-x-twitter"></i>
                        </a>
                    </div>
                    <a href="{{ route('blog') }}" class="btn btn-light btn-sm rounded-pill px-3 fw-bold">
                        ← Xem tất cả bài viết
                    </a>
                </div>
            </div>
        </div>

        <!-- Sidebar (Related articles & Promoted Products) -->
        <div class="col-lg-4">
            <div class="sticky-top" style="top: 90px;">
                <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
                    <h5 class="fw-bold mb-3"><i class="fa-solid fa-fire text-danger me-2"></i> Bài Viết Khác</h5>
                    <div class="d-flex flex-column gap-3">
                        @foreach($relatedArticles as $rel)
                            <div class="d-flex gap-3 align-items-center">
                                <a href="{{ route('blog.detail', $rel['id']) }}" class="flex-shrink-0">
                                    <img src="{{ $rel['image'] }}" alt="{{ $rel['title'] }}" class="rounded-3 object-fit-cover" style="width: 75px; height: 75px;">
                                </a>
                                <div>
                                    <h6 class="mb-1" style="font-size: 0.9rem;">
                                        <a href="{{ route('blog.detail', $rel['id']) }}" class="text-dark text-decoration-none hover-primary line-clamp-2">
                                            {{ $rel['title'] }}
                                        </a>
                                    </h6>
                                    <small class="text-muted">{{ $rel['date'] }}</small>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="card border-0 shadow-sm rounded-4 p-4 text-center text-white" style="background: linear-gradient(135deg, #ff5722 0%, #ea580c 100%);">
                    <i class="fa-solid fa-gift fa-3x mb-3 text-warning"></i>
                    <h5 class="fw-bold mb-2">Ưu Đãi Đặc Quyền</h5>
                    <p class="small opacity-90 mb-3">Nhập mã <strong>SPORTVIP</strong> giảm ngay 10% cho đơn hàng đầu tiên.</p>
                    <a href="{{ route('products.index') }}" class="btn btn-light rounded-pill px-4 fw-bold text-dark">
                        Mua sắm ngay
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
