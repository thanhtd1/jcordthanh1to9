<?php

use Illuminate\Database\Seeder;
use App\Product;
use App\ProductType;
use Faker\Factory;

class productSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Factory::create();
        $Types       = Product::inRandomOrder()->first();
        $clothes     = ['quần tây nam','âu phục nam','váy nữ','đầm công sở nữ', 'đồ trẻ em'];
        $clothes_img = ['quantaynam','auphucnam','vaynu','damcongsonu', 'dotreem'];
        $shoes       = ['giày da nam', 'giày vải nữ','giày trẻ em'];
        $shoes_img   = ['giaydanam', 'giayvainu','giaytreem'];
        $jewelry     = ['vòng đeo tay da', 'vòng đeo tay bạc', 'vòng cổ vàng'];
        $jewelry_img = ['da', 'bac', 'vang'];
        $clothes_id  = ProductType::select('id')->where('name','Clothes')->first();
        $shoes_id  = ProductType::select('id')->where('name','Shoes')->first();
        $jewelry_id  = ProductType::select('id')->where('name','Jewelry')->first();

        foreach ($clothes as $key => $value) {
            $product = new Product;
            $product->name = $value;
            $product->price = round(random_int(100000,5000000),-3);
            $product->quantity=random_int(1,100);
            $product->image= 'img/clothes/'.$clothes_img[$key].'.jpg';
            $product->description = $faker->realText(rand(10,200));
            $product->product_type_id=$clothes_id->id;
            $product->created_at = date("Y-m-d H:i:s");
            $product->updated_at = date("Y-m-d H:i:s");
            $product->save();
        }
        foreach ($shoes as $key => $value) {
            $product = new Product;
            $product->name = $value;
            $product->price = round(random_int(100000,5000000),-3);
            $product->quantity=random_int(1,100);
            $product->image= 'img/shoes/'.$shoes_img[$key].'.jpg';
            $product->description = $faker->realText(rand(10,200));
            $product->product_type_id=$shoes_id->id;
            $product->created_at = date("Y-m-d H:i:s");
            $product->updated_at = date("Y-m-d H:i:s");
            $product->save();
        }
        foreach ($jewelry as $key => $value) {
            $product = new Product;
            $product->name = $value;
            $product->price = round(random_int(100000,5000000),-3);
            $product->quantity=random_int(1,100);
            $product->image= 'img/jewelry/'.$jewelry_img[$key].'.jpg';
            $product->description = $faker->realText(rand(10,200));
            $product->product_type_id=$jewelry_id->id;
            $product->created_at = date("Y-m-d H:i:s");
            $product->updated_at = date("Y-m-d H:i:s");
            $product->save();
        }
    }
}
