<?php

namespace Database\Seeders;

use App\Models\Banner;
use Illuminate\Database\Seeder;

class BannerSeeder extends Seeder
{
    public function run(): void
    {
        $banners = [
            [
                'title' => 'Chinh Phục Mọi Sân Cỏ - Bộ Sưu Tập Bóng Đá 2026',
                'image' => 'https://images.unsplash.com/photo-1517649763962-0c623266ddc0?w=1600&auto=format&fit=crop&q=80',
                'link' => '/products?category=bong-da',
                'position' => 'home_top',
                'status' => 'active',
            ],
            [
                'title' => 'Tốc Độ & Uy Lực Tối Đa - Vợt Cầu Lông Yonex & Li-Ning',
                'image' => 'https://images.unsplash.com/photo-1626224583764-f87db24ac4ea?w=1600&auto=format&fit=crop&q=80',
                'link' => '/products?category=cau-long',
                'position' => 'home_top',
                'status' => 'active',
            ],
            [
                'title' => 'Bứt Phá Giới Hạn - Giày Chạy Bộ & Gym Chuyên Nghiệp',
                'image' => 'https://images.unsplash.com/photo-1517838277536-f5f99be501cd?w=1600&auto=format&fit=crop&q=80',
                'link' => '/products?category=chay-bo',
                'position' => 'home_top',
                'status' => 'active',
            ],
        ];

        foreach ($banners as $b) {
            Banner::firstOrCreate(['title' => $b['title']], $b);
        }
    }
}
