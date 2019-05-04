<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $guarded = [];

    // 产品
    public function product()
    {
        return $this->hasOne('App\Product', 'id', 'product_id');
    }

    // 客户
    public function customer()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

    // 客户
    public function creater()
    {
        return $this->hasOne('App\User', 'id', 'created_by');
    }

    // 店
    public function shop()
    {
        return $this->belongsTo('App\Shop', 'id', 'shop_id');
    }
}
