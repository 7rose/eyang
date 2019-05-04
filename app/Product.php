<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $guarded = [];
    
    // 机构
    public function org()
    {
        return $this->belongsTo('App\Org', 'org_id', 'id');
    }

    // 类型
    public function type()
    {
        return $this->belongsTo('App\Conf', 'type_id', 'id');
    }

    // 订单
    public function orders()
    {
        return $this->belongsTo('App\Order', 'id', 'product_id');
    }
}
