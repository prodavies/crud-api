<?php

namespace App\schema_models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $guarded = [];

    //many to many relationship with orders
    public function orders(){
        return $this->belongsToMany('App\schema_models\Order','order_details','product_id','order_id')->withTimestamps();
    }

    //many to many relationship with suppliers
    public function suppliers(){
        return $this->belongsToMany('App\schema_models\Supplier','supplier_products','product_id','supplier_id')->withTimestamps();
    }
}
