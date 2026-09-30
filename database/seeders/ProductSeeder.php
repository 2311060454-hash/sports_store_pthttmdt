<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::all()->keyBy('slug');
        $brands = Brand::all()->keyBy('slug');

        $productsData = [
            // 1. Bóng đá
            [
                'cat' => 'bong-da', 'brand' => 'nike', 'sku' => 'NIKE-MERC-01',
                'name' => 'Giày Đá Bóng Nike Mercurial Vapor 15 Pro FG',
                'price' => 3200000, 'sale_price' => 2850000, 'qty' => 35, 'sold' => 48, 'featured' => true,
                'short' => 'Dòng giày tốc độ hàng đầu dành cho tiền đạo, đế FG bám sân đỉnh cao.',
                'desc' => 'Được trang bị túi đệm khí Zoom Air 3/4 chiều dài hoàn toàn mới, mang lại cảm giác đàn hồi dưới lòng bàn chân để bạn bứt tốc nhanh chóng trên sân cỏ tự nhiên. Cấu trúc Speed Cage bên trong mỏng nhẹ nhưng ôm chặt bàn chân.',
                'thumb' => 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=600&auto=format&fit=crop&q=80',
                'sizes' => ['39', '40', '41', '42', '43'], 'colors' => ['Đỏ Cam', 'Trắng Ngọc']
            ],
            [
                'cat' => 'bong-da', 'brand' => 'adidas', 'sku' => 'ADI-PRED-02',
                'name' => 'Giày Đá Bóng Adidas Predator Accuracy.1 Low FG',
                'price' => 3500000, 'sale_price' => 3100000, 'qty' => 28, 'sold' => 42, 'featured' => true,
                'short' => 'Kiểm soát bóng chính xác tuyệt đối nhờ lớp cao su công nghệ High Definition Grip.',
                'desc' => 'Adidas Predator Accuracy được chế tạo dành riêng cho những pha xử lý bóng tinh tế và những cú sút hiểm hóc. Thân giày bằng vải dệt HybridTouch mềm mại kết hợp cổ giày dệt PRIMEKNIT hai mảnh ôm sát.',
                'thumb' => 'https://images.unsplash.com/photo-1511556532299-8f662fc26c06?w=600&auto=format&fit=crop&q=80',
                'sizes' => ['40', '41', '42', '43'], 'colors' => ['Đen Hồng', 'Trắng Đen']
            ],
            [
                'cat' => 'bong-da', 'brand' => 'adidas', 'sku' => 'ADI-BALL-03',
                'name' => 'Quả Bóng Đá Adidas UEFA Champions League Pro 2026',
                'price' => 1950000, 'sale_price' => 1750000, 'qty' => 45, 'sold' => 86, 'featured' => true,
                'short' => 'Bóng thi đấu chính thức đạt tiêu chuẩn FIFA Quality Pro danh giá.',
                'desc' => 'Họa tiết ngôi sao kinh điển của cúp C1 Châu Âu với bề mặt không mối nối bằng công nghệ ép nhiệt giúp quỹ đạo bay chuẩn xác và giảm độ hút nước tối đa.',
                'thumb' => 'https://images.unsplash.com/photo-1614632537190-23e4146777db?w=600&auto=format&fit=crop&q=80',
                'sizes' => ['Số 5'], 'colors' => ['Trắng Xanh Cam']
            ],
            [
                'cat' => 'bong-da', 'brand' => 'adidas', 'sku' => 'ADI-GLOV-04',
                'name' => 'Găng Tay Thủ Môn Adidas Predator Pro Có Xương',
                'price' => 1650000, 'sale_price' => null, 'qty' => 18, 'sold' => 29, 'featured' => false,
                'short' => 'Mặt mút URG 2.0 bám dính bóng trong mọi điều kiện thời tiết.',
                'desc' => 'Găng tay thủ môn chuyên nghiệp với xương bảo vệ ngón tay chống lật ngón hiệu quả. Mặt lưng dệt kim co giãn linh hoạt giúp cản phá những cú sút uy lực.',
                'thumb' => 'https://images.unsplash.com/photo-1579952363873-27f3bade9f55?w=600&auto=format&fit=crop&q=80',
                'sizes' => ['Size 8', 'Size 9', 'Size 10'], 'colors' => ['Đen Đỏ']
            ],
            [
                'cat' => 'bong-da', 'brand' => 'nike', 'sku' => 'NIKE-JER-05',
                'name' => 'Áo Đấu Thể Thao Nike Dri-FIT Academy Pro',
                'price' => 650000, 'sale_price' => 499000, 'qty' => 60, 'sold' => 110, 'featured' => false,
                'short' => 'Công nghệ thoát mồ hôi Dri-FIT giúp bạn luôn khô thoáng và mát mẻ.',
                'desc' => 'Chất liệu 100% sợi polyester tái chế thân thiện với môi trường, các dải lưới thông hơi ở lưng và hai bên sườn áo tăng cường luồng khí lưu thông khi vận động cường độ cao.',
                'thumb' => 'https://images.unsplash.com/photo-1521572267360-ee0c2909d518?w=600&auto=format&fit=crop&q=80',
                'sizes' => ['S', 'M', 'L', 'XL'], 'colors' => ['Xanh Navy', 'Đỏ', 'Trắng']
            ],

            // 2. Cầu lông
            [
                'cat' => 'cau-long', 'brand' => 'yonex', 'sku' => 'YON-AST88-06',
                'name' => 'Vợt Cầu Lông Yonex Astrox 88D Pro Chính Hãng',
                'price' => 4150000, 'sale_price' => 3890000, 'qty' => 20, 'sold' => 54, 'featured' => true,
                'short' => 'Cây vợt tấn công cầu sau uy lực nhất thế giới được các tay vợt số 1 lựa chọn.',
                'desc' => 'Công nghệ Rotational Generator System phân bổ trọng lượng tối ưu ở đỉnh đầu, khớp nối và cán vợt giúp người chơi nhanh chóng chuẩn bị cho cú đánh uy lực tiếp theo.',
                'thumb' => 'https://images.unsplash.com/photo-1626224583764-f87db24ac4ea?w=600&auto=format&fit=crop&q=80',
                'sizes' => ['3U-G5', '4U-G5'], 'colors' => ['Camel Gold / Đen']
            ],
            [
                'cat' => 'cau-long', 'brand' => 'li-ning', 'sku' => 'LIN-AERO-07',
                'name' => 'Vợt Cầu Lông Li-Ning Aeronaut 9000 Đỉnh Cao',
                'price' => 3800000, 'sale_price' => 3450000, 'qty' => 15, 'sold' => 31, 'featured' => false,
                'short' => 'Thiết kế rãnh thoát khí độc quyền Aeronaut Technology giảm tối đa lực cản gió.',
                'desc' => 'Khung vợt làm từ sợi carbon siêu cao Nano Alloy giúp tăng độ bền và khả năng chống rung lắc khi tiếp xúc cầu ở tốc độ cao.',
                'thumb' => 'https://images.unsplash.com/photo-1613918108466-292b78a8ef95?w=600&auto=format&fit=crop&q=80',
                'sizes' => ['3U', '4U'], 'colors' => ['Vàng Trắng']
            ],
            [
                'cat' => 'cau-long', 'brand' => 'yonex', 'sku' => 'YON-SHOE-08',
                'name' => 'Giày Cầu Lông Yonex Power Cushion 65Z3 Bám Sân',
                'price' => 2750000, 'sale_price' => 2450000, 'qty' => 4, 'sold' => 67, 'featured' => true, // low stock!
                'short' => 'Đệm Power Cushion+ hấp thụ chấn động và chuyển hóa thành lực đẩy cực tốt.',
                'desc' => 'Đôi giày cầu lông bán chạy nhất của Yonex, đế cao su Radial Blade Sole tăng độ bám khi di chuyển đa hướng và bảo vệ mắt cá chân toàn diện.',
                'thumb' => 'https://images.unsplash.com/photo-1560769629-975ec94e6a86?w=600&auto=format&fit=crop&q=80',
                'sizes' => ['40', '41', '42', '43'], 'colors' => ['Trắng Xanh', 'Đen']
            ],
            [
                'cat' => 'cau-long', 'brand' => 'yonex', 'sku' => 'YON-NF800-09',
                'name' => 'Vợt Cầu Lông Yonex Nanoflare 800 Pro Tốc Độ',
                'price' => 4300000, 'sale_price' => null, 'qty' => 12, 'sold' => 19, 'featured' => false,
                'short' => 'Tốc độ phản tạt chớp nhoáng với công nghệ Sonic Flare System.',
                'desc' => 'Dành cho những người chơi có lối đánh phản tạt, bắt lưới nhanh và thủ phản công chớp nhoáng với thân vợt siêu mỏng giảm lực cản gió.',
                'thumb' => 'https://images.unsplash.com/photo-1626224583764-f87db24ac4ea?w=600&auto=format&fit=crop&q=80',
                'sizes' => ['4U-G5', '3U-G5'], 'colors' => ['Deep Green']
            ],

            // 3. Tennis
            [
                'cat' => 'tennis', 'brand' => 'wilson', 'sku' => 'WIL-STAFF-10',
                'name' => 'Vợt Tennis Wilson Pro Staff v14 (315g)',
                'price' => 4950000, 'sale_price' => 4490000, 'qty' => 16, 'sold' => 25, 'featured' => true,
                'short' => 'Cây vợt biểu tượng huyền thoại của Roger Federer phiên bản mới nhất.',
                'desc' => 'Công nghệ Braid 45 sắp xếp các sợi carbon ở góc 45 độ mang lại cảm giác bóng êm ái cùng độ ổn định vô song trong từng cú đánh uy lực từ cuối sân.',
                'thumb' => 'https://images.unsplash.com/photo-1595435934249-5df7ed86e1c0?w=600&auto=format&fit=crop&q=80',
                'sizes' => ['Cán 2 (4 1/4)', 'Cán 3 (4 3/8)'], 'colors' => ['Nâu Đồng Kim Loại']
            ],
            [
                'cat' => 'tennis', 'brand' => 'wilson', 'sku' => 'WIL-BALL-11',
                'name' => 'Hộp 4 Bóng Tennis Wilson US Open Championship',
                'price' => 180000, 'sale_price' => 150000, 'qty' => 150, 'sold' => 320, 'featured' => false,
                'short' => 'Bóng thi đấu chính thức của giải Grand Slam US Open suốt 40 năm qua.',
                'desc' => 'Lớp dạ nỉ cao cấp Extra Duty chuyên dụng cho mặt sân cứng ngoài trời, duy trì độ nảy chuẩn xác và khả năng chống mài mòn vượt trội.',
                'thumb' => 'https://images.unsplash.com/photo-1534438327276-14e5300c3a48?w=600&auto=format&fit=crop&q=80',
                'sizes' => ['Hộp 4 quả'], 'colors' => ['Vàng Chuẩn']
            ],

            // 4. Bóng rổ
            [
                'cat' => 'bong-ro', 'brand' => 'molten', 'sku' => 'MOL-BG4500-12',
                'name' => 'Quả Bóng Rổ Da Cao Cấp Molten BG4500 Size 7',
                'price' => 1450000, 'sale_price' => 1250000, 'qty' => 30, 'sold' => 74, 'featured' => true,
                'short' => 'Bóng thi đấu chính thức đạt tiêu chuẩn FIBA Approved toàn cầu.',
                'desc' => 'Chất liệu da tổng hợp cao cấp phức hợp, hạt gai bề mặt cải tiến cho độ bám tay tuyệt vời ngay cả khi đổ nhiều mồ hôi trong các trận cầu nảy lửa.',
                'thumb' => 'https://images.unsplash.com/photo-1546519638-68e109498ffc?w=600&auto=format&fit=crop&q=80',
                'sizes' => ['Size 7 (Chuẩn nam)'], 'colors' => ['Cam Phối Trắng']
            ],
            [
                'cat' => 'bong-ro', 'brand' => 'nike', 'sku' => 'NIKE-LEBRON-13',
                'name' => 'Giày Bóng Rổ Nike LeBron 20 All-Star',
                'price' => 4500000, 'sale_price' => 3950000, 'qty' => 14, 'sold' => 38, 'featured' => true,
                'short' => 'Thiết kế cổ thấp bứt tốc, đệm Zoom Air kép trước và sau bàn chân.',
                'desc' => 'Phiên bản kỷ niệm sự nghiệp vĩ đại của LeBron James, mặt đế vân xương cá bám sân tuyệt đối, hỗ trợ bật nhảy và tiếp đất êm ái bảo vệ khớp.',
                'thumb' => 'https://images.unsplash.com/photo-1579338559194-a162d19bf842?w=600&auto=format&fit=crop&q=80',
                'sizes' => ['41', '42', '43', '44'], 'colors' => ['Hồng Cam', 'Đen Tím']
            ],

            // 5. Bóng chuyền
            [
                'cat' => 'bong-chuyen', 'brand' => 'mikasa', 'sku' => 'MIK-V200W-14',
                'name' => 'Quả Bóng Chuyền Da Thi Đấu Olympic Mikasa V200W',
                'price' => 1850000, 'sale_price' => 1650000, 'qty' => 25, 'sold' => 61, 'featured' => true,
                'short' => 'Quả bóng chuyền thi đấu chính thức của FIVB và Olympic.',
                'desc' => 'Thiết kế khí động học 18 múi cân bằng hoàn hảo, bề mặt dập nổi hạt lồi lõm kép Double Dimple giúp kiểm soát hướng bóng chuẩn xác khi chuyền và đập.',
                'thumb' => 'https://images.unsplash.com/photo-1612872087720-bb876e2e67d1?w=600&auto=format&fit=crop&q=80',
                'sizes' => ['Size 5 Chuẩn'], 'colors' => ['Xanh Vàng']
            ],
            [
                'cat' => 'bong-chuyen', 'brand' => 'mikasa', 'sku' => 'MIK-KNEE-15',
                'name' => 'Băng Bảo Vệ Đầu Gối Bóng Chuyền Mikasa Đệm Dày',
                'price' => 320000, 'sale_price' => null, 'qty' => 50, 'sold' => 89, 'featured' => false,
                'short' => 'Bảo vệ khớp gối tối đa khi cứu bóng và tiếp đất.',
                'desc' => 'Đệm mút xốp EVA đàn hồi cao chống sốc, vải co giãn 4 chiều thoáng khí thấm hút mồ hôi tốt, ôm khít chân không bị tuột khi di chuyển.',
                'thumb' => 'https://images.unsplash.com/photo-1584735935682-2f2b69dff9d2?w=600&auto=format&fit=crop&q=80',
                'sizes' => ['M', 'L', 'XL'], 'colors' => ['Đen', 'Xanh']
            ],

            // 6. Gym & Fitness
            [
                'cat' => 'gym-fitness', 'brand' => 'puma', 'sku' => 'PUM-DUMB-16',
                'name' => 'Bộ Tạ Tay Bọc Cao Su Cao Cấp (Cặp 5kg x 2)',
                'price' => 680000, 'sale_price' => 590000, 'qty' => 30, 'sold' => 95, 'featured' => false,
                'short' => 'Lõi gang đúc nguyên khối bọc cao su chống trầy sàn và giảm tiếng ồn.',
                'desc' => 'Tay cầm mạ crom có vân nhám chống trơn trượt, trọng lượng chuẩn xác hỗ trợ tối đa các bài tập cơ tay, vai, ngực tại nhà và phòng tập.',
                'thumb' => 'https://images.unsplash.com/photo-1583454110551-21f2fa2afe61?w=600&auto=format&fit=crop&q=80',
                'sizes' => ['5kg/quả'], 'colors' => ['Đen']
            ],
            [
                'cat' => 'gym-fitness', 'brand' => 'nike', 'sku' => 'NIKE-BAND-17',
                'name' => 'Bộ 5 Dây Kháng Lực Đa Năng PowerBand Tập Gym',
                'price' => 350000, 'sale_price' => 280000, 'qty' => 80, 'sold' => 190, 'featured' => true,
                'short' => 'Chất liệu cao su thiên nhiên 100% siêu dai và đàn hồi cực tốt.',
                'desc' => 'Bao gồm 5 mức kháng lực từ nhẹ đến siêu nặng (10lbs đến 50lbs), thích hợp tập mông đùi, kéo xà đơn, khởi động và giãn cơ sau khi tập.',
                'thumb' => 'https://images.unsplash.com/photo-1598289431512-b97b0917affc?w=600&auto=format&fit=crop&q=80',
                'sizes' => ['Bộ 5 dây'], 'colors' => ['5 Màu Phân Mức']
            ],
            [
                'cat' => 'gym-fitness', 'brand' => 'adidas', 'sku' => 'ADI-YOGA-18',
                'name' => 'Thảm Tập Yoga Adidas Định Tuyến Chống Trượt 8mm',
                'price' => 750000, 'sale_price' => 650000, 'qty' => 40, 'sold' => 82, 'featured' => false,
                'short' => 'Vạch định tuyến chuẩn xác giúp người tập thực hiện đúng tư thế.',
                'desc' => 'Chất liệu TPE sinh học thân thiện với môi trường, độ dày 8mm êm ái bảo vệ khớp xương gối và lưng, độ bám sàn tuyệt đối chống trơn trượt.',
                'thumb' => 'https://images.unsplash.com/photo-1601925260368-ae2f83cf8b7f?w=600&auto=format&fit=crop&q=80',
                'sizes' => ['183cm x 61cm'], 'colors' => ['Xanh Lam', 'Tím', 'Hồng']
            ],
            [
                'cat' => 'gym-fitness', 'brand' => 'puma', 'sku' => 'PUM-BELT-19',
                'name' => 'Đai Lưng Mềm Thể Hình Tập Tạ Squat Deadlift',
                'price' => 450000, 'sale_price' => null, 'qty' => 3, 'sold' => 45, 'featured' => false, // low stock!
                'short' => 'Khóa dán kim loại chịu tải trọng lớn, bảo vệ cột sống thắt lưng.',
                'desc' => 'Thiết kế công thái học ôm sát vòng eo, tạo áp lực ổ bụng giúp tăng sức mạnh khi nâng các mức tạ nặng trong bài tập Squat và Deadlift.',
                'thumb' => 'https://images.unsplash.com/photo-1517838277536-f5f99be501cd?w=600&auto=format&fit=crop&q=80',
                'sizes' => ['M (eo 70-85cm)', 'L (eo 85-100cm)'], 'colors' => ['Đen Đỏ']
            ],

            // 7. Chạy bộ
            [
                'cat' => 'chay-bo', 'brand' => 'nike', 'sku' => 'NIKE-PEG40-20',
                'name' => 'Giày Chạy Bộ Nike Air Zoom Pegasus 40 Êm Ái',
                'price' => 3600000, 'sale_price' => 3190000, 'qty' => 40, 'sold' => 125, 'featured' => true,
                'short' => 'Đôi giày chạy bộ đa năng biểu tượng tròn 40 năm tuổi của Nike.',
                'desc' => 'Sự kết hợp hoàn hảo giữa công nghệ bọt React êm ái và hai túi đệm khí Zoom Air ở mũi và gót chân, mang lại cảm giác phản hồi lực tràn đầy năng lượng trên từng bước chạy.',
                'thumb' => 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=600&auto=format&fit=crop&q=80',
                'sizes' => ['40', '41', '42', '43', '44'], 'colors' => ['Trắng Bạc', 'Đen Trắng']
            ],
            [
                'cat' => 'chay-bo', 'brand' => 'adidas', 'sku' => 'ADI-UB23-21',
                'name' => 'Giày Chạy Bộ Adidas Ultraboost Light 23',
                'price' => 4200000, 'sale_price' => 3700000, 'qty' => 22, 'sold' => 78, 'featured' => true,
                'short' => 'Hạt đệm Boost nhẹ hơn 30% so với thế hệ trước, hoàn trả năng lượng tối đa.',
                'desc' => 'Thân giày dệt PRIMEKNIT+ ôm chân như một chiếc tất, đế ngoài bằng cao su Continental bám dính chắc chắn trên cả mặt đường khô lẫn ướt.',
                'thumb' => 'https://images.unsplash.com/photo-1584735935682-2f2b69dff9d2?w=600&auto=format&fit=crop&q=80',
                'sizes' => ['40', '41', '42', '43'], 'colors' => ['Trắng Tinh', 'Xám Đen']
            ],
            [
                'cat' => 'chay-bo', 'brand' => 'puma', 'sku' => 'PUM-BAG-22',
                'name' => 'Túi Thể Thao Đeo Hông Chạy Bộ Chống Nước',
                'price' => 220000, 'sale_price' => 179000, 'qty' => 90, 'sold' => 240, 'featured' => false,
                'short' => 'Thiết kế siêu mỏng nhẹ, vừa vặn tất cả các dòng điện thoại thông minh.',
                'desc' => 'Chất liệu vải Lycra chống thấm nước mồ hôi và mưa nhẹ, có dải phản quang an toàn khi chạy bộ ban đêm, đai co giãn không bị xóc nảy khi chạy.',
                'thumb' => 'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?w=600&auto=format&fit=crop&q=80',
                'sizes' => ['Free Size'], 'colors' => ['Đen', 'Xanh Dạ Quang']
            ],
            [
                'cat' => 'chay-bo', 'brand' => 'nike', 'sku' => 'NIKE-SOCK-23',
                'name' => 'Set 3 Đôi Tất Thể Thao Chạy Bộ Chống Trượt Nike',
                'price' => 190000, 'sale_price' => 149000, 'qty' => 120, 'sold' => 310, 'featured' => false,
                'short' => 'Dệt dày phần gót và mũi chân chống phồng rộp khi chạy đường dài.',
                'desc' => 'Chất liệu cotton dệt phối thun co giãn thoáng khí, dải thun ôm vòm bàn chân chống xê dịch trong giày.',
                'thumb' => 'https://images.unsplash.com/photo-1582966772680-860e372bb558?w=600&auto=format&fit=crop&q=80',
                'sizes' => ['Size L (39-44)'], 'colors' => ['Set Đen Trắng Xám']
            ],

            // 8. Bơi lội
            [
                'cat' => 'boi-loi', 'brand' => 'nike', 'sku' => 'NIKE-SWIM-24',
                'name' => 'Kính Bơi Thi Đấu Chống Tia UV Tráng Gương Nike',
                'price' => 550000, 'sale_price' => 450000, 'qty' => 35, 'sold' => 92, 'featured' => false,
                'short' => 'Mắt kính tráng gương chống lóa nắng ngoài trời và chống đọng sương Anti-fog.',
                'desc' => 'Vành đệm mắt silicon y tế êm ái chống tràn nước 100%, có kèm theo 3 cầu mũi thay thế phù hợp với mọi khuôn mặt.',
                'thumb' => 'https://images.unsplash.com/photo-1530549387789-4c1017266635?w=600&auto=format&fit=crop&q=80',
                'sizes' => ['Người lớn'], 'colors' => ['Đen Tráng Gương', 'Xanh Biển']
            ],
            [
                'cat' => 'boi-loi', 'brand' => 'adidas', 'sku' => 'ADI-CAP-25',
                'name' => 'Mũ Bơi Silicon Cao Cấp Co Giãn Chống Nước',
                'price' => 180000, 'sale_price' => null, 'qty' => 70, 'sold' => 135, 'featured' => false,
                'short' => 'Bảo vệ tóc và da đầu khỏi hóa chất clo trong nước hồ bơi.',
                'desc' => 'Chất liệu 100% silicon cao cấp không độc hại, độ co giãn vượt trội dễ đội và không gây đau rát tóc khi tháo ra.',
                'thumb' => 'https://images.unsplash.com/photo-1519315901367-f34ff9154487?w=600&auto=format&fit=crop&q=80',
                'sizes' => ['Free Size'], 'colors' => ['Đen', 'Bạc', 'Xanh Dương']
            ],

            // Sản phẩm bổ sung đặc sắc khác
            [
                'cat' => 'gym-fitness', 'brand' => 'puma', 'sku' => 'PUM-DUFF-26',
                'name' => 'Túi Trống Thể Thao Tập Gym Chống Nước Puma 35L',
                'price' => 690000, 'sale_price' => 550000, 'qty' => 25, 'sold' => 68, 'featured' => true,
                'short' => 'Có ngăn riêng biệt đựng giày và quần áo bẩn sau khi tập luyện.',
                'desc' => 'Chất liệu vải Polyester Oxford dày dặn chống trầy xước và chống thấm nước, quai đeo vai có đệm mút trợ lực êm ái.',
                'thumb' => 'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?w=600&auto=format&fit=crop&q=80',
                'sizes' => ['Dung tích 35L'], 'colors' => ['Đen Nhám', 'Xám Khói']
            ],
            [
                'cat' => 'bong-da', 'brand' => 'puma', 'sku' => 'PUM-FUTR-27',
                'name' => 'Giày Đá Bóng Puma Future Ultimate FG/AG',
                'price' => 3100000, 'sale_price' => 2690000, 'qty' => 19, 'sold' => 33, 'featured' => false,
                'short' => 'Công nghệ dải băng FUZIONFIT360 ôm khít mu bàn chân không cần thắt dây.',
                'desc' => 'Được các ngôi sao như Neymar Jr tin dùng, kết cấu 3D Grip ở các vùng tiếp xúc trọng yếu giúp rê dắt và khống chế bóng dính như keo.',
                'thumb' => 'https://images.unsplash.com/photo-1511556532299-8f662fc26c06?w=600&auto=format&fit=crop&q=80',
                'sizes' => ['40', '41', '42'], 'colors' => ['Xanh Lam Neon']
            ],
            [
                'cat' => 'cau-long', 'brand' => 'li-ning', 'sku' => 'LIN-BAG-28',
                'name' => 'Bao Vợt Cầu Lông Li-Ning Chuyên Nghiệp 6 Cây Có Cách Nhiệt',
                'price' => 1100000, 'sale_price' => 950000, 'qty' => 15, 'sold' => 27, 'featured' => false,
                'short' => 'Lớp bạc bạc phản nhiệt bảo vệ cước và khung vợt khỏi thời tiết nóng.',
                'desc' => 'Có thể đựng vừa 6-9 cây vợt cầu lông, giày, quần áo và phụ kiện, quai đeo lưng êm ái chống mỏi.',
                'thumb' => 'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?w=600&auto=format&fit=crop&q=80',
                'sizes' => ['Bao 6 cây'], 'colors' => ['Đen Vàng', 'Trắng Đỏ']
            ],
            [
                'cat' => 'tennis', 'brand' => 'wilson', 'sku' => 'WIL-CLASH-29',
                'name' => 'Vợt Tennis Wilson Clash 100 v2 Êm Khớp',
                'price' => 4600000, 'sale_price' => 4100000, 'qty' => 11, 'sold' => 22, 'featured' => false,
                'short' => 'Dòng vợt thân thiện với người chơi nhất, triệt tiêu chấn động đau khuỷu tay.',
                'desc' => 'Công nghệ FreeFlex cho phép khung uốn cong theo mọi chiều chuyển động của cú đánh, kiểm soát bóng tối đa mà vẫn giữ được lực phát bóng mạnh mẽ.',
                'thumb' => 'https://images.unsplash.com/photo-1595435934249-5df7ed86e1c0?w=600&auto=format&fit=crop&q=80',
                'sizes' => ['Cán 2'], 'colors' => ['Đỏ Đen']
            ],
            [
                'cat' => 'gym-fitness', 'brand' => 'nike', 'sku' => 'NIKE-ROPE-30',
                'name' => 'Dây Nhảy Thể Lực Vòng Bi Thép Tốc Độ Cao Nike',
                'price' => 290000, 'sale_price' => 230000, 'qty' => 0, 'sold' => 140, 'featured' => false, // out of stock = 0!
                'short' => 'Dây cáp thép bọc nhựa PU, xoay vòng bi 360 độ cực êm chống xoắn dây.',
                'desc' => 'Dành cho các bài tập tim mạch Cardio đốt mỡ, rèn luyện sự nhanh nhẹn của đôi chân cho vận động viên bóng đá và boxing.',
                'thumb' => 'https://images.unsplash.com/photo-1598289431512-b97b0917affc?w=600&auto=format&fit=crop&q=80',
                'sizes' => ['Dài 3m tùy chỉnh'], 'colors' => ['Đen', 'Đỏ']
            ],
        ];

        foreach ($productsData as $data) {
            $catId = $categories[$data['cat']]->id ?? 1;
            $brandId = $brands[$data['brand']]->id ?? 1;

            $product = Product::firstOrCreate(
                ['sku' => $data['sku']],
                [
                    'category_id' => $catId,
                    'brand_id' => $brandId,
                    'name' => $data['name'],
                    'slug' => Str::slug($data['name']) . '-' . strtolower(Str::random(4)),
                    'short_description' => $data['short'],
                    'description' => $data['desc'],
                    'price' => $data['price'],
                    'sale_price' => $data['sale_price'],
                    'quantity' => $data['qty'],
                    'thumbnail' => $data['thumb'],
                    'status' => 'active',
                    'featured' => $data['featured'],
                    'views' => rand(150, 2400),
                    'sold' => $data['sold'],
                ]
            );

            if ($product->wasRecentlyCreated) {
                // Add 2 Gallery Images
                ProductImage::create([
                    'product_id' => $product->id,
                    'image' => $data['thumb'],
                ]);
                ProductImage::create([
                    'product_id' => $product->id,
                    'image' => 'https://images.unsplash.com/photo-1517649763962-0c623266ddc0?w=600&auto=format&fit=crop&q=80',
                ]);

                // Add Variants
                if (!empty($data['sizes'])) {
                    foreach ($data['sizes'] as $sIndex => $size) {
                        $color = !empty($data['colors']) ? $data['colors'][$sIndex % count($data['colors'])] : null;
                        ProductVariant::create([
                            'product_id' => $product->id,
                            'size' => $size,
                            'color' => $color,
                            'sku' => $product->sku . '-' . $sIndex,
                            'quantity' => max(0, (int) round($product->quantity / max(1, count($data['sizes'])))),
                            'additional_price' => 0,
                        ]);
                    }
                }
            }
        }
    }
}
