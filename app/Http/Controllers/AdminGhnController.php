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
    public function testConnection(GHNService $ghn)
    {
        $token = $ghn->getToken();
        $shopId = $ghn->getShopId();

        if (empty($token) || empty($shopId)) {
            return response()->json([
                'success' => false,
                'message' => 'Chưa cấu hình GHN_TOKEN hoặc GHN_SHOP_ID. Vui lòng nhập thông số từ tài khoản 5sao.ghn.dev của bạn.',
            ]);
        }

        $res = $ghn->getProvinces();

        if (isset($res['code']) && $res['code'] == 200 && !empty($res['data'])) {
            return response()->json([
                'success' => true,
                'message' => "Kết nối API GHN thành công! Hệ thống sẵn sàng đồng bộ đơn hàng lên bảng điều khiển 5sao.ghn.dev.",
                'data' => $res,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Không thể xác thực với GHN API: ' . ($res['message'] ?? 'Lỗi không xác định'),
        ]);
    }
}
