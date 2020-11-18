<?php

namespace App\schema_models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $guarded = [];
    
    //many to many relationship with products
    public function products(){
        return $this->belongsToMany('App\schema_models\Product','supplier_products','supplier_id','product_id')->withTimestamps();
    }
}
