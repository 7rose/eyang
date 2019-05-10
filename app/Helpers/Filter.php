<?php

namespace App\Helpers;

use Session;
use App\Conf;
use App\Product;

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
        $record = Product::findOrFail($product_id);

        $type = $record->type->val;
        $config = json_decode($record->org->config, true);

        return count($config) && array_has($config, $type) && $config[$type] ? $config[$type] : false;
    }

    /**
     * 报备
     *
     */
    public function bbBackup($order)
    {
        $items = $this->bb($order->product->id);

        if(!$items) return false;

        return array_key_exists('video', $items);
    }

}





















