<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Admin Default Account (if not created by AdminUserSeeder)
        if (!User::where('role', 'admin')->exists()) {
            User::create([
                'name' => 'Quản Trị Viên Hệ Thống',
                'email' => 'admin@sportstore.com',
                'phone' => '0988888888',
                'password' => Hash::make('Admin@12345678'),
                'role' => 'admin',
                'status' => 'active',
                'email_verified_at' => now(),
            ]);
        }

        // 2. Customers
        $customers = [
            ['name' => 'Nguyễn Văn An', 'email' => 'an.nguyen@gmail.com', 'phone' => '0901234561', 'city' => 'TP. Hồ Chí Minh', 'district' => 'Quận 1', 'ward' => 'Phường Bến Nghé', 'address' => '12 Lê Duẩn'],
            ['name' => 'Trần Thị Bình', 'email' => 'binh.tran@gmail.com', 'phone' => '0901234562', 'city' => 'Hà Nội', 'district' => 'Quận Ba Đình', 'ward' => 'Phường Kim Mã', 'address' => '45 Kim Mã'],
            ['name' => 'Lê Hoàng Cường', 'email' => 'cuong.le@gmail.com', 'phone' => '0901234563', 'city' => 'Đà Nẵng', 'district' => 'Quận Hải Châu', 'ward' => 'Phường Thạch Thang', 'address' => '78 Bạch Đằng'],
            ['name' => 'Phạm Minh Dũng', 'email' => 'dung.pham@gmail.com', 'phone' => '0901234564', 'city' => 'TP. Hồ Chí Minh', 'district' => 'Quận 7', 'ward' => 'Phường Tân Phong', 'address' => '102 Nguyễn Thị Thập'],
            ['name' => 'Hoàng Ngọc Em', 'email' => 'em.hoang@gmail.com', 'phone' => '0901234565', 'city' => 'Cần Thơ', 'district' => 'Quận Ninh Kiều', 'ward' => 'Phường An Cư', 'address' => '15 Đại lộ Hòa Bình'],
            ['name' => 'Vũ Quốc Huy', 'email' => 'huy.vu@gmail.com', 'phone' => '0901234566', 'city' => 'Hải Phòng', 'district' => 'Quận Hồng Bàng', 'ward' => 'Phường Minh Khai', 'address' => '88 Điện Biên Phủ'],
            ['name' => 'Đỗ Mai Hương', 'email' => 'huong.do@gmail.com', 'phone' => '0901234567', 'city' => 'Hà Nội', 'district' => 'Quận Cầu Giấy', 'ward' => 'Phường Dịch Vọng', 'address' => '32 Xuân Thủy'],
            ['name' => 'Bùi Tuấn Kiệt', 'email' => 'kiet.bui@gmail.com', 'phone' => '0901234568', 'city' => 'Bình Dương', 'district' => 'TP. Thủ Dầu Một', 'ward' => 'Phường Phú Cường', 'address' => '54 Yersin'],
            ['name' => 'Ngô Bảo Long', 'email' => 'long.ngo@gmail.com', 'phone' => '0901234569', 'city' => 'Đồng Nai', 'district' => 'TP. Biên Hòa', 'ward' => 'Phường Quyết Thắng', 'address' => '99 Võ Thị Sáu'],
            ['name' => 'Dương Thúy Nga', 'email' => 'nga.duong@gmail.com', 'phone' => '0901234570', 'city' => 'Khánh Hòa', 'district' => 'TP. Nha Trang', 'ward' => 'Phường Lộc Thọ', 'address' => '23 Trần Phú'],
        ];

        foreach ($customers as $c) {
            $user = User::firstOrCreate(
                ['email' => $c['email']],
                [
                    'name' => $c['name'],
                    'phone' => $c['phone'],
                    'password' => Hash::make('12345678'),
                    'role' => 'customer',
                    'status' => 'active',
                    'email_verified_at' => now(),
                ]
            );

            Address::firstOrCreate(
                ['user_id' => $user->id, 'address' => $c['address']],
                [
                    'receiver_name' => $c['name'],
                    'phone' => $c['phone'],
                    'province' => $c['city'],
                    'district' => $c['district'],
                    'ward' => $c['ward'],
                    'is_default' => true,
                ]
            );
        }
    }
}
