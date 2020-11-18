<?php

namespace App\schema_models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $guarded = [];

    //many to many relationship with producs
    public function products(){
        return $this->belongsToMany('App\schema_models\Product','order_details','order_id','product_id')->withTimestamps();
    }
}
