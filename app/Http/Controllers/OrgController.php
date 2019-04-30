<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use StringTemplate\Engine;

use App\Org;

class OrgController extends Controller
{
    /**
     * 上家机构
     *
     */
    public function index(Engine $engine)
    {
        $a = Org::find(1)->config;
        $b = json_decode($a);

        $t = $b->templet;

        // $shop = Str::before($t, '=');
        // $la = Str::after($shop, 'business_info/');

        // echo $la;

        // $c = $engine->render($b, ['shop' => 'Nicolò', 'product' => 'Martini']);

        // echo $c;

        // echo $b;
        // print_r($b);
        $str = 'http://rzd.wetwvn.cn/index.php/Home/shop_info/business_info/MTYwMjg=/shop_id/2905';
        // $p = '/http:\/\/rzd.wetwvn.cn\/index.php\/Home\/shop_info\/business_info\/\p{shop}=\/shop_id\/\p{product}/';

        $p = "/^http:\/\/rzd.wetwvn.cn\/index.php\/Home\/shop_info\/business_info\/(?<shop>\w{7})=\/shop_id\/(?<product>\d{4,})/";

        // $result = [];
        preg_match_all($p,$str, $r); 
        echo $r['shop'][0];
        echo $r['product'][0];
// $str="你好<我>(爱)[北京]{天安门}";
// $result = array(); 
// // preg_match_all("/^(.*)(?:<)/i",$str, $result); 
# preg_match_all("/(?:http:\/\/rzd.wetwvn.cn\/index.php\/Home\/shop_info\/business_info\/)(\w{7})(?:=\/)/i",$str, $result); 
        // print_r($result);
// $p = $b->product;
// preg_match_all($p, $str, $result); 

// // echo $b->product;
// // $arr = Str::after("")
// // echo('"'.$b->product.'"');
// return $result[1][0]; 


    }

    /**
     * 
     *
     */
}
















