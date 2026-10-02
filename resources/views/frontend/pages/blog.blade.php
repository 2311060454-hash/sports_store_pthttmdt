@extends('layouts.app')

@section('title', 'Tin Tức Thể Thao & Kinh Nghiệm Tập Luyện - Sport Store')

@section('content')
<!-- Page Header -->
<div class="bg-dark text-white py-5 mb-5" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);">
    <div class="container text-center py-4">
        <span class="badge bg-warning text-dark px-3 py-2 fw-bold text-uppercase mb-3">Blog & Tin Tức</span>
        <h1 class="display-5 fw-extrabold mb-3">Kiến Thức Thể Thao & Mẹo Tập Luyện</h1>
        <p class="lead text-light opacity-75 max-w-700 mx-auto" style="max-width: 700px;">
            Tổng hợp kinh nghiệm chọn trang thiết bị thi đấu, cẩm nang dinh dưỡng và giáo án tập luyện chuẩn khoa học từ các chuyên gia.
        </p>
    </div>
</div>

<div class="container py-3 mb-5">
    <div class="row g-4">
        @foreach($articles as $post)
            <div class="col-lg-6 col-md-12">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100 transition-hover">
                    <div class="row g-0 h-100">
                        <div class="col-md-5">
                            <a href="{{ route('blog.detail', $post['id']) }}">
                                <img src="{{ $post['image'] }}" 
                                     alt="{{ $post['title'] }}" 
                                     class="img-fluid h-100 w-100 object-fit-cover" 
                                     style="min-height: 220px;">
                            </a>
                        </div>
                        <div class="col-md-7 d-flex flex-column p-4">
                            <div>
                                <span class="badge bg-primary-subtle text-primary fw-bold mb-2">
                                    {{ $post['category'] }}
                                </span>
                                <h5 class="fw-bold mb-2">
                                    <a href="{{ route('blog.detail', $post['id']) }}" class="text-dark text-decoration-none hover-primary">
                                        {{ $post['title'] }}
                                    </a>
                                </h5>
                                <p class="text-secondary small mb-3 line-clamp-2">
                                    {{ $post['summary'] }}
                                </p>
                            </div>
                            <div class="mt-auto d-flex justify-content-between align-items-center pt-3 border-top small text-muted">
                                <span><i class="fa-solid fa-user-pen me-1"></i> {{ $post['author'] }}</span>
                                <span><i class="fa-regular fa-calendar me-1"></i> {{ $post['date'] }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
