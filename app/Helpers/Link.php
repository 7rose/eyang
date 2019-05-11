<?php

namespace App\Helpers;

use Illuminate\Support\Str;
use StringTemplate\Engine;

use App\Org;
use App\Shop;
use App\Helpers\Info;

/**
 * 链接生成  
 *
 */
class Link
{
    /**
     * 生成链接
     *
     */
    public function link($product)
    {
        $info = new Info;
        $shop = $info->shop;

        $tag = $product->org->code;

        $shop_code = $this->getCode($shop->config, $tag);
        $product_code = $this->getCode($product->config, $tag);

        $templet = $this->getCode($product->org->config, 'templet');

        if(!$shop_code || !$product_code || !$templet) return false;

        $engine = new Engine;

        $link = $engine->render($templet, ['shop' => $shop_code, 'product' => $product_code]);

        return urldecode($link);
    }

    /**
     * 获取josn配置
     *
     */
    public function getCode($json, $key)
    {
        $jc = collect(json_decode($json));

        if(!$jc->count()) return false;
        if(!$jc->has($key)) return false;

        return $jc[$key];
    }


    /**
     * 更新数据库信息
     *
     */
    public function saveShopCode($org_id, $url, $shop_id=0)
    {
        $info = new Info;

        $target_shop_id = $shop_id === 0 ? $info->id() : $shop_id;

        $shop_code = $this->getShopCode($org_id, $url);

        if(!$shop_code) return false;

        $target = Shop::findOrFail($target_shop_id);

        $org = Org::findOrFail($org_id);

        $target->update(['config->'.$org->code => $shop_code ]);

        return $shop_code;
    }

    /**
     * 店编码
     *
     */
    public function getShopCode($org_id, $url)
    {
        $array = $this->getMatch($org_id, $this->getRealUrl($url));

        if(!$array) return false;

        return $array['shop'][0];
    }

    /**
     * 产品编码
     *
     */
    public function getProductCode($org_id, $url)
    {
        $array = $this->getMatch($org_id, $this->getRealUrl($url));

        if(!$array) return false;

        return $array['product'][0];
    }

    /**
     * 获取匹配数组
     *
     */
    public function getMatch($org_id, $url)
    {
        preg_match_all($this->setRule($org_id),$url, $array); 

        if(!count($array['shop']) || !count($array['product'])) return false;
        return $array;
    }
    
    /**
     * 检查
     *
     */
    public function getConfig($org_id)
    {
        $target = Org::findOrFail($org_id);
        $config = collect(json_decode($target->config));

        $ex = $config->has(['templet', 'shop', 'product']);

        if(!$ex) return false;
        if(!Str::contains($config['templet'], '{shop}') || !Str::contains($config['templet'], '{product}')) return false;

        return $config;
    }

    /**
     * 生成正则表达式
     *
     */
    public function setRule($org_id)
    {
        $conf = $this->getConfig($org_id);

        if(!$conf) return false;

        $r = $conf['templet'];

        $r = str_replace("/","\/",$r);
        $r = str_replace("{shop}","(?<shop>\\".$conf['shop'].')',$r);
        $r = str_replace("{product}","(?<product>\\".$conf['product'].')',$r);
        $r = "/".$r."/";

        return $r;
    }

    /**
     * 获取真实链接
     *
     */
    public function getRealUrl($url, $timeout=20)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);

        curl_setopt($ch, CURLOPT_VERBOSE, true);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        // curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout - 10); # 连接超时
        curl_setopt($ch, CURLOPT_AUTOREFERER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        // 下面两行为不验证证书和 HOST，建议在此前判断 URL 是否是 HTTPS
        // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        // curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        // $ret 返回跳转信息
        curl_exec($ch);
        // $info 以 array 形式返回跳转信息
        $info = curl_getinfo($ch);
        // 跳转后的 URL 信息
        return urlencode($info['url']); # 编码链接
    }


    /**
     *
     *
     */
}
















