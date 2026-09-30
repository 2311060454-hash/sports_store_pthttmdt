@extends('layouts.app')

@section('title', '404 - Không Tìm Thấy Trang')

@section('content')
<div class="container py-5 text-center my-5">
    <div class="rounded-circle bg-warning-subtle text-warning d-inline-flex align-items-center justify-content-center mb-4" style="width: 100px; height: 100px;">
        <i class="fa-solid fa-magnifying-glass fs-1"></i>
    </div>
    <h1 class="display-3 fw-extrabold text-primary mb-2">404</h1>
    <h3 class="fw-bold mb-3">Không Tìm Thấy Trang Yêu Cầu</h3>
    <p class="text-muted lead mb-4" style="max-width: 500px; margin: 0 auto;">
        Đường dẫn sản phẩm hoặc trang bạn đang tìm kiếm có thể đã được gỡ bỏ, đổi tên hoặc tạm thời không khả dụng.
    </p>
    <div class="d-flex justify-content-center gap-3">
        <a href="{{ route('home') }}" class="btn btn-primary rounded-pill px-4 py-2 fw-bold shadow">
            <i class="fa-solid fa-house me-2"></i> Trang Chủ
        </a>
        <a href="{{ route('products.index') }}" class="btn btn-outline-secondary rounded-pill px-4 py-2 fw-bold">
            Xem Sản Phẩm
        </a>
    </div>
</div>
@endsection
