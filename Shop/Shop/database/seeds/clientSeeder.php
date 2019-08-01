<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory;
use Illuminate\Support\Facades\Hash;

class clientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Factory::create();
        
        $string = 'user';
        for($i=1;$i<=100;$i++){
            if($i<10){
                $num = '00' .$i;
            }
            elseif($i>10 && $i<100){
                $num = '0' .$i;
            }
            else{
                $num = $i;
            }
            DB::table('client')->insert([
                'name' => $string . $num,
                'username' =>$string . $num,
                'password' =>Hash::make($string . $num),
                'sex' => rand(0,1),
                'birthday'=>(date('Y')-(rand(10,50))) .'-' .rand(1,12) .'-' .rand(1,28),
                'hometown'=>$faker->address,
                'address'=>$faker->address,
                'email'=>$faker->unique()->email,
                'phone'=>$faker->unique()->phoneNumber,
                'created_at'=>date("Y-m-d H:i:s"),
                'updated_at'=>date("Y-m-d H:i:s")
            ]);
        }
        DB::table('client')->insert([
            'name' => 'Nguyễn Ngọc Anh Thư',
            'username' =>'user123',
            'password' =>Hash::make('user123'),
            'sex'=>0,
            'birthday'=>'1996-11-17',
            'hometown'=>'Lấp Vò, Đồng Tháp',
            'address'=>'đường 232 Cao lỗ, p4, q8, tp HCM',
            'email'=>'meothu@thumeo.com',
            'phone'=>'0377435879',
            'created_at'=>date("Y-m-d H:i:s"),
            'updated_at'=>date("Y-m-d H:i:s")
        ]);

        DB::table('client')->insert([
            'name' => 'Nguyễn Thế Mạnh',
            'username' =>'user126',
            'password' =>Hash::make('user126'),
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
