<?php

namespace App\Helpers;

use Session;
use App\Conf;
use App\Product;
use App\Order;
use App\Org;

/**
 * Filter
 *
 */
class Filter
{

    private $keywords;

    function __construct()
    {
        $this->keywords = Session::has('keywords') ? trim(session('keywords')) : '';
    }
    
    /**
     * fit
     *
     * 不区分大小写
     *
     */
    public function fit($text, $keyName=0)
    {
        if(!$keyName == 0) $this->keywords = Session::has($keyName) ? trim(session($keyName)) : '';

        return str_ireplace($this->keywords, '<strong class="text-warning">'.$this->keywords.'</strong>', $text);
    }

    /**
     * show conf
     *
     */
     public function showConf($id)
     {
        if(is_array($id) && Session::has('search_level_id')) {
            $target = Conf::findOrFail(session('search_level_id'));
        }else{
            $target = Conf::findOrFail($id);
        }

        return $target->key;
     }

    /**
     * show json
     *
     */
    public function show($json, $key, $flag=0) 
    {
        try {
            $arr = json_decode($json);
            if($arr && array_key_exists($key, $arr)){
                return $arr->$key;
            } else {
                return $flag === 0 ? null : $flag;
            }

        } catch (Exception $e) {
            return $flag === 0 ? null : $flag;
            exit();
        }

    }

    /**
     * 报备
     *
     */
    public function bb($product_id)
    {
        $config = $this->orgConfig($product_id);
        $type = $this->productType($product_id);

        return count($config) && array_has($config, $type) && $config[$type] ? $config[$type] : false;
    }

    /**
     * 获取供应商配置数组
     *
     */
    public function productType($product_id)
    {
        $record = Product::findOrFail($product_id);

        return $record->type->val;
    }

    /**
     * 获取供应商配置数组
     *
     */
    public function orgConfig($product_id)
    {
        $record = Product::findOrFail($product_id);

        $type = $record->type->val;

        return json_decode($record->org->config, true);
    }

    /**
     * 当需要录制视频时,可以使用密码表单
     *
     */
    public function bbBackup($order)
    {
        $items = $this->bb($order->product->id);

        if(!$items) return false;

        return array_key_exists('video', $items);
    }

    /**
     * 报备有效
     *
     * @return carbon
     */
    public function bbTime($order_id)
    {
        $order = Order::findOrFail($order_id);

        $config = $this->orgConfig($order->product_id);
        $type = $this->productType($order->product_id);

        if(count($config) && array_has($config, $type.'_expire')) {
            $ex = $config[$type.'_expire'];

            $end = $order->created_at->startOfDay()->addDays($ex['days'])->addHours($ex['hours'])->addMinutes($ex['minutes']);

            if($end->gt(now())) return $end;
        }

        return false;
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
     * 下架
     *
     * @return carbon
     */
    public function onLine($product)
    {
        $config = $this->orgConfig($product->id);
        $type = $this->productType($product->id);

        if(count($config) && array_has($config, $type.'_from') && array_has($config, $type.'_to')) {

            $from = $config[$type.'_from'];
            $from_time = today()->addHours($from['hours'])->addMinutes($from['minutes']);

            $to = $config[$type.'_to'];
            $to_time = today()->addHours($to['hours'])->addMinutes($to['minutes']);

            if(!now()->between($from_time, $to_time)) return false;
        }

        return true;
    }

}





















