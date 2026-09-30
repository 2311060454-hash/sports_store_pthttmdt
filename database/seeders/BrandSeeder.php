<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    public function run(): void
    {
        $brands = [
            [
                'name' => 'Nike',
                'slug' => 'nike',
                'description' => 'Tập đoàn thể thao số 1 thế giới với công nghệ đệm khí Air Zoom vượt trội.',
                'status' => 'active',
            ],
            [
                'name' => 'Adidas',
                'slug' => 'adidas',
                'description' => 'Thương hiệu thể thao đến từ Đức nổi danh với đệm Boost và dòng Predator huyền thoại.',
                'status' => 'active',
            ],
            [
                'name' => 'Puma',
                'slug' => 'puma',
                'description' => 'Thương hiệu thể thao báo đốm dẫn đầu về tốc độ và phong cách thể thao hiện đại.',
                'status' => 'active',
            ],
            [
                'name' => 'Yonex',
                'slug' => 'yonex',
                'description' => 'Thương hiệu cầu lông và tennis số 1 Nhật Bản được các tay vợt huyền thoại tin dùng.',
                'status' => 'active',
            ],
            [
                'name' => 'Li-Ning',
                'slug' => 'li-ning',
                'description' => 'Thương hiệu thể thao cao cấp châu Á chuyên vợt cầu lông và giày thể thao công nghệ tiên tiến.',
                'status' => 'active',
            ],
            [
                'name' => 'Wilson',
                'slug' => 'wilson',
                'description' => 'Huyền thoại dụng cụ quần vợt và bóng rổ chuẩn NBA từ nước Mỹ.',
                'status' => 'active',
            ],
            [
                'name' => 'Mikasa',
                'slug' => 'mikasa',
                'description' => 'Thương hiệu bóng chuyền và bóng nước chính thức cho các kỳ Olympic.',
                'status' => 'active',
            ],
            [
                'name' => 'Molten',
                'slug' => 'molten',
                'description' => 'Nhà sản xuất bóng rổ và bóng đá thi đấu chính thức của FIBA và các giải đấu châu lục.',
                'status' => 'active',
            ],
        ];

        foreach ($brands as $b) {
            Brand::firstOrCreate(['slug' => $b['slug']], $b);
        }
    }
}
