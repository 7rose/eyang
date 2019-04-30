<?php

namespace App\Helpers;

use Carbon\Carbon;

use App\Product;

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
     * 
     *
     */
}














