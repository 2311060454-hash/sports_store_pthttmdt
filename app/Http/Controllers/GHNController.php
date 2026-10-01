<?php

namespace App\Http\Controllers;

use App\Services\GHNService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class GHNController extends Controller
{
    /**
     * Trang tính cước phí vận chuyển GHN công khai
     */
    public function calculatorPage()
    {
        $shopInfo = [
            'name' => 'Sport Store - Dụng Cụ Thể Thao',
            'address' => config('services.ghn.return_address', '123 Cầu Giấy, Phường Dịch Vọng, Quận Cầu Giấy, Hà Nội'),
            'from_district_id' => (int) config('services.ghn.from_district_id', 1442),
            'from_ward_code' => (string) config('services.ghn.from_ward_code', '20508'),
        ];

        return view('frontend.pages.shipping-calculator', compact('shopInfo'));
    }

    public function getProvinces(GHNService $ghn)
    {
        return response()->json($ghn->getProvinces());
    }

    public function getDistricts(int $provinceId, GHNService $ghn)
    {
        return response()->json($ghn->getDistricts($provinceId));
    }

    public function getWards(int $districtId, GHNService $ghn)
    {
        return response()->json($ghn->getWards($districtId));
    }

    /**
     * Tính cước phí dựa trên giỏ hàng (cho Checkout và Cart)
     */
    public function getShippingFee(Request $request, GHNService $ghn)
    {
        $request->validate([
            'to_district_id' => 'required|integer',
            'to_ward_code' => 'required|string',
        ]);

        $cart = session('cart', []);
        $weight = 0;
        $insuranceValue = 0;

        foreach ($cart as $item) {
            $itemWeight = (int) ($item['weight'] ?? config('services.ghn.default_weight', 200));
            $qty = (int) ($item['quantity'] ?? 1);
            $weight += $itemWeight * $qty;
            $insuranceValue += (int) ($item['price'] ?? 0) * $qty;
        }

        if ($weight === 0) {
            $weight = (int) config('services.ghn.default_weight', 300);
        }

        $fromDistrictId = (int) config('services.ghn.from_district_id', 1442);
        $fromWardCode = (string) config('services.ghn.from_ward_code', '20508');

        $res = $ghn->calculateFee([
            'from_district_id' => $fromDistrictId,
            'from_ward_code' => $fromWardCode,
            'to_district_id' => (int) $request->to_district_id,
            'to_ward_code' => (string) $request->to_ward_code,
            'service_type_id' => 2,
            'weight' => $weight,
            'length' => 20,
            'width' => 15,
            'height' => 10,
            'insurance_value' => min($insuranceValue, 5000000),
        ]);

        return response()->json($res);
    }

    /**
     * Tính cước phí chi tiết tùy biến (Cho trang Tra cứu cước và Admin)
     */
    public function calculateCustomFee(Request $request, GHNService $ghn)
    {
        $request->validate([
            'to_district_id' => 'required|integer',
            'to_ward_code' => 'required|string',
            'weight' => 'nullable|integer|min:50|max:50000',
            'length' => 'nullable|integer|min:1|max:200',
            'width' => 'nullable|integer|min:1|max:200',
            'height' => 'nullable|integer|min:1|max:200',
            'insurance_value' => 'nullable|integer|min:0',
        ]);

        $fromDistrictId = (int) config('services.ghn.from_district_id', 1442);
        $fromWardCode = (string) config('services.ghn.from_ward_code', '20508');
        $weight = (int) ($request->weight ?: 500);
        $length = (int) ($request->length ?: 25);
        $width = (int) ($request->width ?: 15);
        $height = (int) ($request->height ?: 15);
        $insuranceValue = (int) ($request->insurance_value ?: 0);

        $res = $ghn->calculateFee([
            'from_district_id' => $fromDistrictId,
            'from_ward_code' => $fromWardCode,
            'to_district_id' => (int) $request->to_district_id,
            'to_ward_code' => (string) $request->to_ward_code,
            'service_type_id' => 2,
            'weight' => $weight,
            'length' => $length,
            'width' => $width,
            'height' => $height,
            'insurance_value' => min($insuranceValue, 5000000),
        ]);

        // Thời gian giao hàng dự kiến
        $isSameCity = ($fromDistrictId == (int) $request->to_district_id || (int)$request->province_id == 201);
        $expectedDeliveryDays = $isSameCity ? '1 - 2 ngày làm việc' : '2 - 3 ngày làm việc';
        $expectedDate = Carbon::now()->addDays($isSameCity ? 1 : 2)->format('d/m/Y');

        return response()->json([
            'success' => true,
            'ghn_response' => $res,
            'expected_time' => $expectedDeliveryDays,
            'expected_date' => $expectedDate,
            'weight' => $weight,
            'dimensions' => "{$length} x {$width} x {$height} cm",
        ]);
    }
}
