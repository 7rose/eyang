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
     * 过期
     *
     */
    public function ok($record)
    {
        $end = $record->created_at->startOfDay()->addHours(21)->addMinutes(30);

        return $end->gt(now()) ? $end->diffForHumans() : false;
    }

    /**
     * 报备但未审批
     *
     */
    public function submit($record)
    {
        return isset($record->bb) && !$record->bb->check;
    }

    /**
     * 报备但未审批
     *
     */
    public function check($record)
    {
        return isset($record->bb) && $record->bb->check;
    }

    /**
     * 报备结果
     *
     */
    public function submitResault($record)
    {
        return isset($record->bb) && $record->bb->success;
    }

    /**
     * 审批结果
     *
     */
    public function checkResault($record)
    {
        return isset($record->bb) && $record->bb->resault;
    }




    /**
     * 
     *
     */
}














