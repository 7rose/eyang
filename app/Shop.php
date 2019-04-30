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

}
