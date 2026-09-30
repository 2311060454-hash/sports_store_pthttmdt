<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Bóng đá',
                'slug' => 'bong-da',
                'description' => 'Giày đá bóng sân cỏ tự nhiên, sân cỏ nhân tạo, bóng đá chuẩn thi đấu FIFA, găng tay thủ môn và phụ kiện.',
                'image' => 'https://images.unsplash.com/photo-1579952363873-27f3bade9f55?w=500&auto=format&fit=crop&q=60',
                'status' => 'active',
            ],
            [
                'name' => 'Cầu lông',
                'slug' => 'cau-long',
                'description' => 'Vợt cầu lông công thủ toàn diện, cước căng vợt, giày cầu lông chuyên dụng bám sân, quả cầu lông và bao vợt.',
                'image' => 'https://images.unsplash.com/photo-1626224583764-f87db24ac4ea?w=500&auto=format&fit=crop&q=60',
                'status' => 'active',
            ],
            [
                'name' => 'Tennis',
                'slug' => 'tennis',
                'description' => 'Vợt tennis trợ lực, bóng quần vợt chuẩn thi đấu quốc tế, túi đựng vợt và phụ kiện tennis cao cấp.',
                'image' => 'https://images.unsplash.com/photo-1595435934249-5df7ed86e1c0?w=500&auto=format&fit=crop&q=60',
                'status' => 'active',
            ],
            [
                'name' => 'Bóng rổ',
                'slug' => 'bong-ro',
                'description' => 'Bóng rổ da cao cấp trong nhà và ngoài trời, giày bóng rổ cổ cao bảo vệ cổ chân, phụ kiện bảo hộ.',
                'image' => 'https://images.unsplash.com/photo-1546519638-68e109498ffc?w=500&auto=format&fit=crop&q=60',
                'status' => 'active',
            ],
            [
                'name' => 'Bóng chuyền',
                'slug' => 'bong-chuyen',
                'description' => 'Quả bóng chuyền da mềm Mikasa, lưới thi đấu, băng gối đệm bảo vệ khớp gối chuyên nghiệp.',
                'image' => 'https://images.unsplash.com/photo-1612872087720-bb876e2e67d1?w=500&auto=format&fit=crop&q=60',
                'status' => 'active',
            ],
            [
                'name' => 'Gym & Fitness',
                'slug' => 'gym-fitness',
                'description' => 'Tạ tay, dây kháng lực, thảm tập yoga, đai lưng tập tạ, con lăn bụng và thiết bị tập thể hình tại nhà.',
                'image' => 'https://images.unsplash.com/photo-1517838277536-f5f99be501cd?w=500&auto=format&fit=crop&q=60',
                'status' => 'active',
            ],
            [
                'name' => 'Chạy bộ',
                'slug' => 'chay-bo',
                'description' => 'Giày chạy bộ êm ái đàn hồi cao, đai đeo điện thoại chạy bộ, bình nước thể thao và tất chống trượt.',
                'image' => 'https://images.unsplash.com/photo-1476480862126-209bfaa8edc8?w=500&auto=format&fit=crop&q=60',
                'status' => 'active',
            ],
            [
                'name' => 'Bơi lội',
                'slug' => 'boi-loi',
                'description' => 'Kính bơi chống sương mù tia UV, mũ bơi silicon, quần bơi thể thao và phao tập bơi chuyên dụng.',
                'image' => 'https://images.unsplash.com/photo-1530549387789-4c1017266635?w=500&auto=format&fit=crop&q=60',
                'status' => 'active',
            ],
        ];

        foreach ($categories as $cat) {
            Category::firstOrCreate(['slug' => $cat['slug']], $cat);
        }
    }
}
