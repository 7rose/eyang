<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    protected $guarded = [];
    
    // 消费者
    public function shop()
    {
        return $this->belongsTo('App\Shop', 'shop_id', 'id');
    }

    // 订单
    public function orders()
    {
        return $this->hasMany('App\Order', 'user_id', 'id');
    }

    // 客户
    public function creater()
    {
        return $this->belongsTo('App\Order', 'id', 'created_by');
    }

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
