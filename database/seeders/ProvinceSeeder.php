<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProvinceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $provinces = array(
            array('code' => '01', 'name' => 'Hà Nội'),
            array('code' => '02', 'name' => 'Hà Giang'),
            array('code' => '04', 'name' => 'Cao Bằng'),
            array('code' => '06', 'name' => 'Bắc Kạn'),
            array('code' => '08', 'name' => 'Tuyên Quang'),
            array('code' => '10', 'name' => 'Lào Cai'),
            array('code' => '11', 'name' => 'Điện Biên'),
            array('code' => '12', 'name' => 'Lai Châu'),
            array('code' => '14', 'name' => 'Sơn La'),
            array('code' => '15', 'name' => 'Yên Bái'),
            array('code' => '17', 'name' => 'Hoà Bình'),
            array('code' => '19', 'name' => 'Thái Nguyên'),
            array('code' => '20', 'name' => 'Lạng Sơn'),
            array('code' => '22', 'name' => 'Quảng Ninh'),
            array('code' => '24', 'name' => 'Bắc Giang'),
            array('code' => '25', 'name' => 'Phú Thọ'),
            array('code' => '26', 'name' => 'Vĩnh Phúc'),
            array('code' => '27', 'name' => 'Bắc Ninh'),
            array('code' => '30', 'name' => 'Hải Dương'),
            array('code' => '31', 'name' => 'Hải Phòng'),
            array('code' => '33', 'name' => 'Hưng Yên'),
            array('code' => '34', 'name' => 'Thái Bình'),
            array('code' => '35', 'name' => 'Hà Nam'),
            array('code' => '36', 'name' => 'Nam Định'),
            array('code' => '37', 'name' => 'Ninh Bình'),
            array('code' => '38', 'name' => 'Thanh Hoá'),
            array('code' => '40', 'name' => 'Nghệ An'),
            array('code' => '42', 'name' => 'Hà Tĩnh'),
            array('code' => '44', 'name' => 'Quảng Bình'),
            array('code' => '45', 'name' => 'Quảng Trị'),
            array('code' => '46', 'name' => 'Thừa Thiên Huế'),
            array('code' => '48', 'name' => 'Đà Nẵng'),
            array('code' => '49', 'name' => 'Quảng Nam'),
            array('code' => '51', 'name' => 'Quảng Ngãi'),
            array('code' => '52', 'name' => 'Bình Định'),
            array('code' => '54', 'name' => 'Phú Yên'),
            array('code' => '56', 'name' => 'Khánh Hoà'),
            array('code' => '58', 'name' => 'Ninh Thuận'),
            array('code' => '60', 'name' => 'Bình Thuận'),
            array('code' => '62', 'name' => 'Kon Tum'),
            array('code' => '64', 'name' => 'Gia Lai'),
            array('code' => '66', 'name' => 'Đắk Lắk'),
            array('code' => '67', 'name' => 'Đắk Nông'),
            array('code' => '68', 'name' => 'Lâm Đồng'),
            array('code' => '70', 'name' => 'Bình Phước'),
            array('code' => '72', 'name' => 'Tây Ninh'),
            array('code' => '74', 'name' => 'Bình Dương'),
            array('code' => '75', 'name' => 'Đồng Nai'),
            array('code' => '77', 'name' => 'Bà Rịa - Vũng Tàu'),
            array('code' => '79', 'name' => 'Hồ Chí Minh'),
            array('code' => '80', 'name' => 'Long An'),
            array('code' => '82', 'name' => 'Tiền Giang'),
            array('code' => '83', 'name' => 'Bến Tre'),
            array('code' => '84', 'name' => 'Trà Vinh'),
            array('code' => '86', 'name' => 'Vĩnh Long'),
            array('code' => '87', 'name' => 'Đồng Tháp'),
            array('code' => '89', 'name' => 'An Giang'),
            array('code' => '91', 'name' => 'Kiên Giang'),
            array('code' => '92', 'name' => 'Cần Thơ'),
            array('code' => '93', 'name' => 'Hậu Giang'),
            array('code' => '94', 'name' => 'Sóc Trăng'),
            array('code' => '95', 'name' => 'Bạc Liêu'),
            array('code' => '96', 'name' => 'Cà Mau')
        );

        DB::table('provinces')->insert($provinces);
    }
}
