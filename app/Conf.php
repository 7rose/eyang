<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Conf extends Model
{
    protected $guarded = [];
    
    // 消费者
    public function products()
    {
        return $this->hasMany('App\Product', 'type_id', 'id');
    }
}
