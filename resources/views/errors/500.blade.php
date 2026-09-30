@extends('layouts.app')

@section('title', '500 - Lỗi Máy Chủ')

@section('content')
<div class="container py-5 text-center my-5">
    <div class="rounded-circle bg-secondary-subtle text-secondary d-inline-flex align-items-center justify-content-center mb-4" style="width: 100px; height: 100px;">
        <i class="fa-solid fa-server fs-1"></i>
    </div>
    <h1 class="display-3 fw-extrabold text-secondary mb-2">500</h1>
    <h3 class="fw-bold mb-3">Đã Xảy Ra Lỗi Hệ Thống</h3>
    <p class="text-muted lead mb-4" style="max-width: 500px; margin: 0 auto;">
        Hệ thống đang gặp sự cố gián đoạn tạm thời. Đội ngũ kỹ thuật của Sport Store đang tích cực xử lý. Vui lòng thử lại sau ít phút.
    </p>
    <a href="{{ route('home') }}" class="btn btn-primary rounded-pill px-4 py-2 fw-bold shadow">
        <i class="fa-solid fa-house me-2"></i> Trở Về Trang Chủ
    </a>
</div>
@endsection
