<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Baobei extends Model
{
    protected $guarded = [];

    // 订单
    public function order()
    {
        return $this->belongsTo('App\Order', 'order_id', 'id');
    }
}
