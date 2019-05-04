<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    protected $guarded = [];
    
    // 消费者
    public function users()
    {
        return $this->hasMany('App\User', 'shop_id', 'id');
    }

    // 分店
    public function subs()
    {
        return $this->hasMany('App\Shop', 'parent_id', 'id');
    }

    // 上级
    public function upper()
    {
        return $this->belongsTo('App\Shop', 'parent_id', 'id');
    }

    // 订单
    public function orders()
    {
        return $this->hasMany('App\Order', 'shop_id', 'id');
    }

}
