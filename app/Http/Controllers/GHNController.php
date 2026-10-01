<?php

namespace App\Http\Controllers;

use App\Services\GHNService;
use Illuminate\Http\Request;

class GHNController extends Controller
{
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

    public function getShippingFee(Request $request, GHNService $ghn)
    {
        $request->validate([
            'to_district_id' => 'required|integer',
            'to_ward_code' => 'required|string',
        ]);

        $cart = session('cart', []);
        $weight = 0;
        foreach ($cart as $item) {
            $itemWeight = (int) ($item['weight'] ?? config('services.ghn.default_weight', 200));
            $weight += $itemWeight * (int) ($item['quantity'] ?? 1);
        }

        if ($weight === 0) {
            $weight = (int) config('services.ghn.default_weight', 300);
        }

        $res = $ghn->calculateFee(array_merge([
            'from_district_id' => (int) config('services.ghn.from_district_id', 1450),
            'to_district_id' => (int) $request->to_district_id,
            'to_ward_code' => (string) $request->to_ward_code,
        ], $ghn->packageParameters($weight)));

        return response()->json($res);
    }
}
