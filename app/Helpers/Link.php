<?php

namespace App\Helpers;

// use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use StringTemplate\Engine;

use App\Org;
use App\Helpers\Info;

/**
 * 动态链接生成 
 *
 */
class Link
{
    private $keys;

    function __construct()
    {
        $this->keys = ['templet', 'product', 'shop', 'param'];
    }

    /**
     * 生成完整链接
     *
     */
    public function link($product, $shop_id=0)
    {
        $product_templet = $this->getProductTemplet($product);
        $shop = $this->getShopCode($product->org_id, $shop_id);

        $engine = new Engine;

        $link = $engine->render($product_templet, ['shop' => $shop]);

        return urldecode($link);
    }

    /**
     * 提取店编码
     *
     */
    public function getShopCode($org_id, $shop_id=0)
    {
        $shop = $this->selectShop($shop_id);

        $array = json_decode($shop->config, true);

        $org = Org::findOrFail($org_id);

        return Arr::has($array, $org->code) ? $array[$org->code] : false;

    }

    /**
     * 提取产品模板
     *
     */
    public function getProductTemplet($product)
    {
        // $shop = $this->selectShop($productshop_id);

        $array = json_decode($product->config, true);

        return (Arr::has($array, 'templet')) ? $array['templet'] : false;

    }

    /**
     * 储存店编码
     *
     */
    public function saveShopCode($org_id, $url, $shop_id=0)
    {
        $shop = $this->selectShop($shop_id);

        $math = $this->getMatch($org_id, $url);
        $shop_code = $math['shop'][0];

        $org = Org::findOrFail($org_id);

        $shop->update(['config->'.$org->code => $shop_code]);

        return $shop_code;

    }

    /**
     * 选择店
     *
     */
    public function selectShop($shop_id=0)
    {
        $info = new Info;

        return $shop_id === 0 ? $info->shop : Shop::findOrFail($shop_id);
    }

    /**
     * 生成产品模板
     *
     */
    public function buildProductTemplet($org_id, $url)
    {
        $org_config = $this->getOrgConfig($org_id);
        $templet = $org_config['templet'];

        $settings = $this->getMatch($org_id, $url);
        $product_code = $settings['product'][0];
        $param = $settings['param'][0];

        $engine = new Engine;

        $link = $engine->render($templet, ['param' => $param, 'product' => $product_code]);

        return $link;
    }

    /**
     * 获取匹配数组
     *
     */
    public function getMatch($org_id, $url)
    {
        preg_match_all($this->buildPreg($org_id), $this->getUrl($url), $array); 

        if(!count($array['shop']) || !count($array['product']) || !count($array['param']) || !count($array['param'])) return false;

        return $array;
    }

    /**
     * 获取供应商配置
     *
     */
    private function getOrgConfig($org_id)
    {
        $org = Org::findOrFail($org_id);

        $array = json_decode($org->config, true);

        return $this->check($array) ? $array : false;
    }

    /**
     * 校验
     *
     */
    private function check($array)
    {
        return Arr::has($array, $this->keys);
    }

    /**
     * 生成正则表达式
     *
     */
    public function buildPreg($org_id)
    {
        $conf = $this->getOrgConfig($org_id);

        if(!$conf) return false;

        $r = $conf['templet'];

        $r = str_replace("{param}","(?<param>".$conf['param'].')',$r);
        $r = str_replace("{shop}","(?<shop>\\".$conf['shop'].')',$r);
        $r = str_replace("{product}","(?<product>\\".$conf['product'].')',$r);
        $r = "/^".$r."/";

        return $r;
    }

    /**
     * 获取真实链接
     *
     */
    public function getUrl($url, $timeout=20)
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
}