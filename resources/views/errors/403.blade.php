@extends('layouts.app')

@section('title', '403 - Quyền Truy Cập Bị Từ Chối')

@section('content')
<div class="container py-5 text-center my-5">
    <div class="rounded-circle bg-danger-subtle text-danger d-inline-flex align-items-center justify-content-center mb-4" style="width: 100px; height: 100px;">
        <i class="fa-solid fa-lock fs-1"></i>
    </div>
    <h1 class="display-3 fw-extrabold text-danger mb-2">403</h1>
    <h3 class="fw-bold mb-3">Truy Cập Bị Từ Chối (Forbidden)</h3>
    <p class="text-muted lead mb-4" style="max-width: 500px; margin: 0 auto;">
        {{ $exception->getMessage() ?: 'Bạn không có quyền quản trị để truy cập vào khu vực này.' }}
    </p>
    <a href="{{ route('home') }}" class="btn btn-primary rounded-pill px-4 py-2 fw-bold shadow">
        <i class="fa-solid fa-house me-2"></i> Quay Lại Trang Chủ
    </a>
</div>
@endsection
