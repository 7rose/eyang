<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Org extends Model
{
    protected $guarded = [];
    
    // 消费者
    public function products()
    {
        return $this->hasMany('App\Product', 'org_id', 'id');
    }
}
