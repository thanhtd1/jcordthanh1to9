<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{    
    protected $table='product' ;
    protected $fillable = [
        'name', 'price', 'quantity', 'description', 'image', 'product_type_id'
    ];
    public function type(){
        return $this->belongsTo('App\ProductType');
    }
}
