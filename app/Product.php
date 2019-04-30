<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $guarded = [];
    
    // 消费者
    public function org()
    {
        return $this->belongsTo('App\Org', 'org_id', 'id');
    }

    // 消费者
    public function type()
    {
        return $this->belongsTo('App\Conf', 'type_id', 'id');
    }
}
