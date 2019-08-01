<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use App\ProductType;
use Validator;
use Illuminate\Support\MessageBag;

class productController extends Controller
{
    public function getList()
    {
        $list = Product::with('type')->orderBy('id', 'desc')->paginate(10);
        return response()->json($list, 200);
    }
    public function getType()
    {
        $types = ProductType::all();
        return view('admin.modal.add_product', ['types'=>$types]);
    }
    public function getDetail($id){
        $product = Product::where('id',$id)->with('type')->first();
        return view('admin.product_detail', ['product' => $product]);
    }
    public function add(Request $req){
        $rules = [
    		'name' =>'required|string',
    		'type' => 'required',
    		'quantity' => 'required|integer|min:1',
    		'price' => 'required|integer|min:1000',
    	];
    	$messages = [
    		'name.required' => 'Tên mặt hàng là bắt buộc',
    		'name.string' => 'Tên đăng nhập không đúng định dạng',
    		'type.required' => 'Loại mặt hàng là trường bắt buộc',
    		'quantity.required' => 'Số lượng là bắt buộc',
    		'quantity.min' => 'Số lượng không được nhỏ hơn 1',
    		'price.required' => 'Giá sản phẩm là bắt buộc',
    		'price.min' => 'Giá sản phẩm tối thiểu 1000',
    	];
    	$validator = Validator::make($req->all(), $rules, $messages);

    	if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
		} 
		else {
            $new_product = Product::create([ 
                "name" => $req->name,
                "product_type_id" => $req->type,
                "quantity" => $req->quantity,
                "price" => $req->price,
                "image" => "img/notification/1.jpg"
            ]);
            // dd($new_product);
            // return view('admin.product_detail',['product'=>$new_product]);
            return $this->getList();
        }
    }
}
