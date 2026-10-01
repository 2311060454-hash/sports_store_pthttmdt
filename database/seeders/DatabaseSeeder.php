<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::transaction(function () {
            $this->call([
                AdminUserSeeder::class,
                UserSeeder::class,
                CategorySeeder::class,
                BrandSeeder::class,
                ProductSeeder::class,
                CouponSeeder::class,
                BannerSeeder::class,
                OrderSeeder::class,
                ChatMessageSeeder::class,
            ]);
        });
    }
}
