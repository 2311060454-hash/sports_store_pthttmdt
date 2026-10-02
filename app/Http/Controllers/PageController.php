<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\Coupon;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PageController extends Controller
{
    /**
     * About Us page
     */
    public function about()
    {
        return view('frontend.pages.about');
    }

    /**
     * Contact Us page
     */
    public function contact()
    {
        return view('frontend.pages.contact');
    }

    /**
     * Handle Contact form submission
     */
    public function contactSubmit(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:150',
            'phone' => 'nullable|string|max:20',
            'subject' => 'nullable|string|max:150',
            'message' => 'required|string|min:10|max:2000',
        ], [
            'name.required' => 'Vui lòng nhập họ và tên của bạn.',
            'email.required' => 'Vui lòng cung cấp email liên hệ.',
            'email.email' => 'Địa chỉ email không đúng định dạng.',
            'message.required' => 'Vui lòng nhập nội dung tin nhắn.',
            'message.min' => 'Nội dung tin nhắn tối thiểu 10 ký tự.',
        ]);

        Contact::create($validated);

        return back()->with('success', 'Cảm ơn bạn đã liên hệ! Ban quản trị Sport Store sẽ phản hồi trong thời gian sớm nhất.');
    }

    /**
     * Promotions & Vouchers page
     */
    public function promotions()
    {
        $now = Carbon::now();

        // Active coupons
        $coupons = Coupon::where('status', 'active')
            ->where('start_date', '<=', $now)
            ->where('end_date', '>=', $now)
            ->whereColumn('used', '<', 'quantity')
            ->get();

        // Hot discounted products
        $discountedProducts = Product::with(['category', 'brand'])
            ->where('status', 'active')
            ->whereNotNull('sale_price')
            ->whereColumn('sale_price', '<', 'price')
            ->orderByRaw('((price - sale_price) / price) DESC')
            ->paginate(12);

        return view('frontend.pages.promotions', compact('coupons', 'discountedProducts'));
    }

    /**
     * FAQ (Câu hỏi thường gặp)
     */
    public function faq()
    {
        return view('frontend.pages.faq');
    }

    /**
     * Policy (Chính sách mua hàng, đổi trả & bảo mật)
     */
    public function policy()
    {
        return view('frontend.pages.policy');
    }

    /**
     * Blog / Tin tức thể thao & hướng dẫn
     */
    public function blog()
    {
        $articles = $this->getBlogArticles();
        return view('frontend.pages.blog', compact('articles'));
    }

    /**
     * Chi tiết bài viết Blog
     */
    public function blogDetail($id)
    {
        $articles = $this->getBlogArticles();
        $article = collect($articles)->firstWhere('id', (int) $id);

        if (!$article) {
            abort(404, 'Bài viết không tồn tại.');
        }

        $relatedArticles = collect($articles)->where('id', '!=', $id)->take(3);

        return view('frontend.pages.blog-detail', compact('article', 'relatedArticles'));
    }

    /**
     * Danh sách bài viết tin tức mẫu chất lượng cao
     */
    private function getBlogArticles(): array
    {
        return [
            [
                'id' => 1,
                'title' => 'Bí quyết chọn vợt cầu lông phù hợp cho người mới bắt đầu và phong trào',
                'category' => 'Tư Vấn Chọn Đồ',
                'author' => 'HLV Nguyễn Tuấn',
                'date' => '28/09/2026',
                'image' => 'https://images.unsplash.com/photo-1626224583764-f87db24ac4ea?w=800&auto=format&fit=crop&q=80',
                'summary' => 'Hướng dẫn chi tiết về trọng lượng 3U/4U, độ dẻo thân vợt và điểm cân bằng giúp bạn chọn cây vợt ưng ý và phát huy tối đa lực đánh.',
                'content' => '
                    <p>Khi mới bắt đầu chơi cầu lông hoặc chuyển từ sân bê tông sang sân thảm tiêu chuẩn, việc chọn một cây vợt đúng thông số là yếu tố quyết định giúp bạn tiến bộ nhanh chóng và tránh được các chấn thương cổ tay, bả vai.</p>
                    <h5>1. Trọng lượng vợt (U)</h5>
                    <p>Hầu hết người chơi phong trào tại Việt Nam nên chọn vợt có thông số <strong>4U (80g - 84g)</strong>. Trọng lượng này vừa đủ đầm tay để tạo lực smash tốt nhưng vẫn linh hoạt khi thủ cầu và phản tạt.</p>
                    <h5>2. Điểm cân bằng (Balance Point)</h5>
                    <ul>
                        <li><strong>Vợt nhẹ đầu (Dưới 285mm):</strong> Thủ phản tạt nhanh, phù hợp đánh lưới và thủ cầu.</li>
                        <li><strong>Vợt cân bằng (285mm - 295mm):</strong> Công thủ toàn diện, sự lựa chọn an toàn nhất cho người mới bắt đầu.</li>
                        <li><strong>Vợt nặng đầu (Trên 295mm):</strong> Tấn công uy lực, smash cắm sân, đòi hỏi lực cổ tay tốt.</li>
                    </ul>
                    <h5>3. Độ cứng của thân vợt</h5>
                    <p>Người mới nên chọn thân vợt <em>dẻo (Flexible)</em> hoặc <em>trung bình (Medium)</em> để có trợ lực tốt hơn khi phông cầu từ cuối sân này sang cuối sân kia.</p>
                '
            ],
            [
                'id' => 2,
                'title' => 'So sánh giày bóng đá sân cỏ nhân tạo: Đinh TF vs đinh AG loại nào tốt hơn?',
                'category' => 'Kinh Nghiệm Thể Thao',
                'author' => 'Minh Chiến Sport',
                'date' => '25/09/2026',
                'image' => 'https://images.unsplash.com/photo-1511886929837-354d827aae26?w=800&auto=format&fit=crop&q=80',
                'summary' => 'Phân tích ưu nhược điểm giữa đế đinh dăm cao su TF và đinh nhựa AG để bạn không còn băn khoăn khi đá bóng trên sân cỏ nhân tạo tại Việt Nam.',
                'content' => '
                    <p>Mặt sân cỏ nhân tạo tại Việt Nam phần lớn có lớp cỏ khá cùn và lượng cao su không đồng đều. Việc chọn đúng loại đinh giày giúp bạn bám sân tốt, chống lật sơ mi và bảo vệ khớp gối tối đa.</p>
                    <h5>1. Đinh TF (Turf) - Đinh dăm cao su</h5>
                    <p>Đinh TF là dòng giày phổ biến nhất chiếm đến 85% sân bóng phong trào. Đế làm bằng cao su với hàng trăm mấu đinh nhỏ, độ bám sân rất êm ái, bẻ hướng linh hoạt và giảm thiểu tối đa áp lực lên mắt cá chân.</p>
                    <h5>2. Đinh AG (Artificial Grass) - Đinh nhựa cọc ngắn</h5>
                    <p>Đinh AG làm bằng nhựa đúc cứng hoặc carbon, giúp bám sân tốt hơn khi trời mưa hoặc trên các cụm sân cỏ mới đạt chuẩn FIFA. Tuy nhiên, nếu đá trên sân cỏ mòn, đinh AG dễ gây đau bàn chân nếu bạn chưa quen.</p>
                '
            ],
            [
                'id' => 3,
                'title' => '5 bài tập thể lực và cơ bắp toàn diện tại nhà không cần dụng cụ đắt tiền',
                'category' => 'Tập Luyện & Sức Khỏe',
                'author' => 'PT Trần Đăng',
                'date' => '20/09/2026',
                'image' => 'https://images.unsplash.com/photo-1517838277536-f5f99be501cd?w=800&auto=format&fit=crop&q=80',
                'summary' => 'Tăng sức bền, đốt mỡ và giữ dáng mỗi ngày chỉ với 25 phút tập luyện Calisthenics kết hợp tạ đơn ngay tại phòng khách của bạn.',
                'content' => '
                    <p>Dù bạn không có nhiều thời gian đến phòng gym mỗi ngày, chỉ cần 20-30 phút tập luyện có kế hoạch tại nhà là bạn đã có thể duy trì thể lực dẻo dai và cơ bắp săn chắc.</p>
                    <h5>1. Hít đất (Push-up) - Phát triển ngực, vai và tay sau</h5>
                    <p>Thực hiện 4 hiệp, mỗi hiệp từ 12-15 cái. Chú ý giữ lưng thẳng, siết chặt cơ bụng và hạ người chậm rãi.</p>
                    <h5>2. Squat bodyweight - Sức mạnh đôi chân</h5>
                    <p>Cơ đùi và mông là nhóm cơ lớn nhất cơ thể, tập squat giúp đốt calo hiệu quả và tăng sức bật khi chơi các môn bóng đá, bóng rổ hay cầu lông.</p>
                    <h5>3. Plank & Mountain Climber - Siết cơ core bền bỉ</h5>
                    <p>Giữ tư thế plank trong 45-60 giây để rèn luyện độ thăng bằng và sức mạnh vùng cơ bụng.</p>
                '
            ],
            [
                'id' => 4,
                'title' => 'Cách bảo quản và căng cước vợt tennis đúng chuẩn giúp tăng tuổi thọ và độ nảy',
                'category' => 'Bảo Quản Dụng Cụ',
                'author' => 'Chuyên gia Tennis',
                'date' => '15/09/2026',
                'image' => 'https://images.unsplash.com/photo-1595435934249-5df7ed86e1c0?w=800&auto=format&fit=crop&q=80',
                'summary' => 'Những thói quen tưởng chừng vô hại như để vợt trong cốp xe hay chọn sai số kg căng cước đang làm giảm đáng kể tuổi thọ cây vợt yêu quý của bạn.',
                'content' => '
                    <p>Một cây vợt tennis chính hãng có giá từ 3 đến 6 triệu đồng, vì vậy việc nắm vững kỹ thuật bảo dưỡng vợt và dây cước là điều mà bất kỳ tay vợt nào cũng cần biết.</p>
                    <h5>1. Tránh nhiệt độ cao trong cốp xe</h5>
                    <p>Nhiệt độ cốp xe vào mùa hè có thể lên tới 50-60 độ C, làm biến tính sợi carbon và khiến cước bị chùng nhanh gấp 3 lần bình thường.</p>
                    <h5>2. Lựa chọn mức căng cước (Tension) thích hợp</h5>
                    <p>Mức căng tiêu chuẩn cho người chơi trung bình là từ 22kg đến 24kg (48 - 53 lbs). Căng quá căng sẽ dễ làm đau khuỷu tay (Tennis Elbow), căng quá chùng sẽ khó kiểm soát điểm rơi của quả bóng.</p>
                '
            ],
        ];
    }

    /**
     * Generate dynamic sitemap.xml for search engines (SEO)
     */
    public function sitemap()
    {
        $products = Product::where('status', 'active')->select('slug', 'updated_at')->get();
        $categories = \App\Models\Category::where('status', 'active')->select('slug', 'updated_at')->get();
        $articles = $this->getBlogArticles();

        $staticPages = [
            ['url' => route('home'), 'priority' => '1.0', 'freq' => 'daily'],
            ['url' => route('products.index'), 'priority' => '0.9', 'freq' => 'daily'],
            ['url' => route('promotions'), 'priority' => '0.8', 'freq' => 'daily'],
            ['url' => route('orders.tracking'), 'priority' => '0.8', 'freq' => 'daily'],
            ['url' => route('shipping.calculator'), 'priority' => '0.7', 'freq' => 'monthly'],
            ['url' => route('blog'), 'priority' => '0.7', 'freq' => 'weekly'],
            ['url' => route('faq'), 'priority' => '0.6', 'freq' => 'monthly'],
            ['url' => route('policy'), 'priority' => '0.6', 'freq' => 'monthly'],
            ['url' => route('about'), 'priority' => '0.5', 'freq' => 'monthly'],
            ['url' => route('contact'), 'priority' => '0.5', 'freq' => 'monthly'],
        ];

        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

        foreach ($staticPages as $page) {
            $xml .= '<url>';
            $xml .= '<loc>' . htmlspecialchars($page['url']) . '</loc>';
            $xml .= '<changefreq>' . $page['freq'] . '</changefreq>';
            $xml .= '<priority>' . $page['priority'] . '</priority>';
            $xml .= '</url>';
        }

        foreach ($categories as $cat) {
            $xml .= '<url>';
            $xml .= '<loc>' . htmlspecialchars(route('products.index', ['category' => $cat->slug])) . '</loc>';
            $xml .= '<lastmod>' . ($cat->updated_at ? $cat->updated_at->toAtomString() : now()->toAtomString()) . '</lastmod>';
            $xml .= '<changefreq>weekly</changefreq>';
            $xml .= '<priority>0.8</priority>';
            $xml .= '</url>';
        }

        foreach ($products as $p) {
            $xml .= '<url>';
            $xml .= '<loc>' . htmlspecialchars(route('products.show', $p->slug)) . '</loc>';
            $xml .= '<lastmod>' . ($p->updated_at ? $p->updated_at->toAtomString() : now()->toAtomString()) . '</lastmod>';
            $xml .= '<changefreq>weekly</changefreq>';
            $xml .= '<priority>0.8</priority>';
            $xml .= '</url>';
        }

        foreach ($articles as $art) {
            $xml .= '<url>';
            $xml .= '<loc>' . htmlspecialchars(route('blog.detail', $art['id'])) . '</loc>';
            $xml .= '<changefreq>monthly</changefreq>';
            $xml .= '<priority>0.6</priority>';
            $xml .= '</url>';
        }

        $xml .= '</urlset>';

        return response($xml, 200, ['Content-Type' => 'application/xml; charset=utf-8']);
    }

    /**
     * Newsletter Subscription handler
     */
    public function newsletter(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|max:150',
        ], [
            'email.required' => 'Vui lòng nhập địa chỉ email của bạn.',
            'email.email' => 'Địa chỉ email không đúng định dạng.',
        ]);

        Contact::create([
            'name' => 'Khách Đăng Ký Bản Tin',
            'email' => $validated['email'],
            'subject' => '[Newsletter] Đăng ký nhận thông tin ưu đãi',
            'message' => 'Khách hàng quan tâm muốn nhận thông báo ưu đãi và coupon giảm giá từ Sport Store.',
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Cảm ơn bạn đã đăng ký! Sport Store sẽ gửi thông tin khuyến mãi mới nhất qua email.',
            ]);
        }

        return back()->with('success', 'Đăng ký nhận bản tin khuyến mãi thành công! Chúng tôi đã lưu thông tin của bạn.');
    }
}
