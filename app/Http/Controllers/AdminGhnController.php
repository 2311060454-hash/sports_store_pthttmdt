<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\GHNService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class AdminGhnController extends Controller
{
    /**
     * Hiển thị bảng điều khiển và cấu hình GHN Sandbox
     */
    public function index(GHNService $ghn)
    {
        $config = [
            'base_url' => config('services.ghn.base_url'),
            'token' => config('services.ghn.token'),
            'shop_id' => config('services.ghn.shop_id'),
            'from_district_id' => config('services.ghn.from_district_id'),
        ];

        // Lấy danh sách các đơn hàng có liên quan tới GHN
        $ghnOrders = Order::whereNotNull('ghn_order_code')
            ->orderBy('updated_at', 'desc')
            ->paginate(15);

        return view('admin.ghn.index', compact('config', 'ghnOrders'));
    }

    /**
     * Cập nhật thông số GHN Token & Shop ID vào .env
     */
    public function updateSettings(Request $request)
    {
        $request->validate([
            'ghn_token' => 'required|string',
            'ghn_shop_id' => 'required|numeric',
            'ghn_from_district_id' => 'required|numeric',
        ]);

        $envPath = base_path('.env');
        if (File::exists($envPath)) {
            $envContent = File::get($envPath);

            $replacements = [
                'GHN_TOKEN' => trim($request->ghn_token),
                'GHN_SHOP_ID' => trim($request->ghn_shop_id),
                'GHN_FROM_DISTRICT_ID' => trim($request->ghn_from_district_id),
            ];

            foreach ($replacements as $key => $value) {
                if (preg_match("/^{$key}=.*/m", $envContent)) {
                    $envContent = preg_replace("/^{$key}=.*/m", "{$key}={$value}", $envContent);
                } else {
                    $envContent .= "\n{$key}={$value}";
                }
            }

            File::put($envPath, $envContent);
        }

        return back()->with('success', 'Cập nhật cấu hình kết nối Giao Hàng Nhanh (GHN) thành công!');
    }

    /**
     * Kiểm tra kết nối tới GHN Sandbox (Test Connection)
     */
    public function testConnection(GHNService $ghn, Request $request)
    {
        $token = trim((string) ($request->input('token') ?: $ghn->getToken()));
        $shopId = trim((string) ($request->input('shop_id') ?: $ghn->getShopId()));

        if (empty($token) || empty($shopId)) {
            return response()->json([
                'success' => false,
                'message' => 'Chưa cấu hình GHN API Token hoặc Shop ID. Vui lòng nhập thông số từ tài khoản 5sao.ghn.dev của bạn.',
            ]);
        }

        try {
            $response = \Illuminate\Support\Facades\Http::baseUrl(config('services.ghn.base_url', 'https://dev-online-gateway.ghn.vn/shiip/public-api'))
                ->withOptions(['verify' => false])
                ->timeout(12)
                ->withHeaders([
                    'Token' => $token,
                    'ShopId' => (string) $shopId,
                    'Content-Type' => 'application/json',
                ])
                ->get('/master-data/province');

            $data = $response->json();

            if ($response->successful() && isset($data['code']) && $data['code'] == 200 && !empty($data['data'])) {
                $count = count($data['data']);
                return response()->json([
                    'success' => true,
                    'message' => "Kết nối API GHN thành công! Đã tải $count Tỉnh/Thành phố. Hệ thống sẵn sàng đồng bộ đơn hàng sang 5sao.ghn.dev.",
                    'data' => $data,
                ]);
            }

            $errMsg = $data['message'] ?? ($data['code_message_value'] ?? 'Xác thực không thành công (HTTP ' . $response->status() . ')');
            return response()->json([
                'success' => false,
                'message' => 'GHN phản hồi lỗi: ' . $errMsg . '. Vui lòng kiểm tra lại mã Token API hoặc Shop ID!',
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi kết nối tới máy chủ GHN: ' . $e->getMessage(),
            ]);
        }
    }
}
