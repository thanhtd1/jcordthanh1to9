<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class productTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $types = array('Clothes','Shoes','Jewelry');
        foreach ($types as $key => $type) {
            
        DB::table('product_type')->insert([
            'name' => $type,
            'created_at'=>date("Y-m-d H:i:s"),
            'updated_at'=>date("Y-m-d H:i:s"),
        ]);
        }
    }
}
