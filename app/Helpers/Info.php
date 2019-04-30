<?php

namespace App\Helpers;

use Auth;

use App\Shop;
use App\Role;
use App\Org;

/**
 * 门店
 *
 */
class Info
{
    private $shop;
    private $info;
    private $config;
    
    function __construct()
    {
        // $fake = "szdai.xyz";
        // $fake = "redniu.top";
        $fake = "eyang.xyz";

        $domain = config('app.env') == 'production' ? $_SERVER['HTTP_HOST'] : $fake;

        $ex = Shop::where('domain', $domain)->first();

        if(!$ex) abort('403');

        $this->shop = $ex;
        $this->info = collect(json_decode($this->shop->info));
        $this->config = collect(json_decode($this->shop->config));
    }

    /**
     * 字段检查
     *
     */
    public function check()
    {

        return $this->info->has(['name','wechat']);

    }

    /**
     * 域名
     *
     */
    public function domain()
    {
        return $this->shop->domain;
    }

    /**
     * id
     *
     */
    public function id()
    {
        return $this->shop->id;
    }

    /**
     * 显示
     *
     */
    public function show($key)
    {
        if(!$this->check()) return false;
        if(!$this->info->has([$key])) return false;

        return $this->info[$key];
    }

    /**
     * 提示设置账号
     *
     */
    public function lackOrgIds()
    {
        $orgs = Org::all();

        $out = [];

        foreach ($orgs as $org) {
            if(!$this->config->has($org->code)) array_push($out, $org->id);
        }

        return $out;
    }

}




















