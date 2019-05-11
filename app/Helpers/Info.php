<?php

namespace App\Helpers;

use Auth;

use App\Shop;
use App\Role;
use App\Org;
use App\Order;
use App\Product;
use App\Helpers\Filter;
use App\Helpers\Picker;

/**
 * 门店
 *
 */
class Info
{
    public $shop;
    private $info;
    private $config;
    
    function __construct()
    {
        // $fake = "szdai.xyz";
        // $fake = "redniu.top";
        $fake = "eyang.xyz";
        // $fake = "mmdai.xyz";
        // $fake = "joydai.xyz";
        // $fake = "ytdai.xyz";

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

    // 产品未激活
    public function lackProduct($product_id)
    {
        $product = Product::find($product_id);

        return in_array($product->org_id, $this->lackOrgIds());
    }

    /**
     * 提示设置账号
     *
     */
    public function bbNum()
    {
        $records = Order::where('user_id', Auth::id())
                    ->whereDoesntHave('bb', function($query){
                        // $query->whereNull('bb');
                    })

                    // ->whereHas('product', function($q1) {
                    //     $q1->whereHas('org', function($q2) {
                    //         $q2->where('code', 'rzd');
                    //     });
                    // })
                    ->get();
                    // ->count();
        $f = new Filter;
        $p = new Picker;

        $num = 0;

        foreach ($records as $record) {
            if($f->bb($record->product->id) && $p->ok($record)) $num ++;
        }

        return $num > 0 ? $num : null;
    }    

}




















