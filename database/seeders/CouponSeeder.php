<?php

namespace Database\Seeders;

use App\Models\Coupon;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class CouponSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        $coupons = [
            [
                'code' => 'SPORT10',
                'type' => 'percent',
                'value' => 10,
                'min_order_amount' => 300000,
                'max_discount' => 100000,
                'start_date' => $now->copy()->subDays(5),
                'end_date' => $now->copy()->addMonths(6),
                'quantity' => 200,
                'used' => 24,
                'status' => 'active',
            ],
            [
                'code' => 'TET2026',
                'type' => 'percent',
                'value' => 15,
                'min_order_amount' => 500000,
                'max_discount' => 200000,
                'start_date' => $now->copy()->subDays(2),
                'end_date' => $now->copy()->addMonths(3),
                'quantity' => 100,
                'used' => 18,
                'status' => 'active',
            ],
            [
                'code' => 'GIAM50K',
                'type' => 'fixed',
                'value' => 50000,
                'min_order_amount' => 250000,
                'max_discount' => null,
                'start_date' => $now->copy()->subDays(10),
                'end_date' => $now->copy()->addMonths(2),
                'quantity' => 150,
                'used' => 45,
                'status' => 'active',
            ],
            [
                'code' => 'FREESHIP',
                'type' => 'fixed',
                'value' => 30000,
                'min_order_amount' => 200000,
                'max_discount' => null,
                'start_date' => $now->copy()->subDays(15),
                'end_date' => $now->copy()->addMonths(5),
                'quantity' => 500,
                'used' => 88,
                'status' => 'active',
            ],
            [
                'code' => 'VIPPRO',
                'type' => 'percent',
                'value' => 20,
                'min_order_amount' => 1000000,
                'max_discount' => 500000,
                'start_date' => $now->copy()->subDays(30),
                'end_date' => $now->copy()->addMonths(12),
                'quantity' => 50,
                'used' => 12,
                'status' => 'active',
            ],
        ];

        foreach ($coupons as $c) {
            Coupon::firstOrCreate(['code' => $c['code']], $c);
        }
    }
}
