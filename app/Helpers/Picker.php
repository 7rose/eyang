<?php

namespace App\Helpers;

use Carbon\Carbon;

use App\Product;
use App\Order;

/**
 * 门店
 *
 */
class Picker
{
    private $hours; # 新品时限

    function __construct()
    {
        $this->hours = 6;
    }

    /**
     * 新品
     *
     */
    public function fresh($id)
    {
        $target = Product::findOrFail($id);
        $end = $target->updated_at->addHours($this->hours);

        return $end->gt(now());
    }

    /**
     * 过期时间
     *
     */
    public function orderValid($record)
    {
        $end = $record->created_at->startOfDay()->addHours(21.5);

        return $end->gt(now()) ? $end->diffForHumans() : false;
    }


    /**
     * 
     *
     */
}














