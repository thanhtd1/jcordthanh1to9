<?php

use Illuminate\Database\Seeder;

class adminsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('admins')->insert([
            'name' => 'Nguyễn Ngọc Anh Thư',
            'username' =>'admin1',
            'password' =>Hash::make('admin1'),
            'sex'=>0,
            'birthday'=>'1996-11-17',
            'hometown'=>'Lấp Vò, Đồng Tháp',
            'address'=>'đường 232 Cao lỗ, p4, q8, tp HCM',
            'email'=>'meothu@thumeo.com',
            'phone'=>'0377435879',
            'created_at'=>date("Y-m-d H:i:s"),
            'updated_at'=>date("Y-m-d H:i:s")
        ]);

        DB::table('admins')->insert([
            'name' => 'Nguyễn Thế Mạnh',
            'username' =>'admin2',
            'password' =>Hash::make('admin2'),
            'sex'=>1,
            'birthday'=>'1996-01-26',
            'hometown'=>'Quỳ Hợp, Nghệ An',
            'address'=>'180 Cao Lỗ, p4, q8, tp HCM',
            'email'=>'nguyenthemanh26011996@gmail.com',
            'phone'=>'0966173668',
            'created_at'=>date("Y-m-d H:i:s"),
            'updated_at'=>date("Y-m-d H:i:s")
        ]);
    }
}
