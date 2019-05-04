<?php

namespace App\Http\Controllers;

use Kris\LaravelFormBuilder\FormBuilderTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Session;

use App\Shop;
use App\Forms\ShopActiveForm;
use App\Helpers\Link;

class ShopController extends Controller
{
    use FormBuilderTrait;

    /**
     * 列表
     *
     */
    public function index()
    {
        $records = Shop::where('level',  1)
                        ->get();


        // foreach ($records as $k) {
        //     echo $k->upper->domain;
        // }

        return view('shops.shops', compact('records'));
    }

    /**
     * 激活表单
     *
     */
    public function active()
    {
         $form = $this->form(ShopActiveForm::class, [
            'method' => 'POST',
            'url' => '/shops/active/do'
        ]);

        $title = '激活: <a href="#" class="btn btn-sm btn-outline-primary"><small>需要帮助?</small></a>';
        $icon = 'magic';

        return view('form', compact('form','title','icon'));
    }

    /**
     * 激活
     *
     */
    public function doActive(Request $request, Link $link)
    {
        $all = $request->all();
        Arr::forget($all, '_token');

        foreach ($all as $org_id => $url) {
            if(trim($url)) {
                $resault = $link->saveShopCode(intval($org_id), $url);
                if(!$resault) return $this->fail();
            }
        }

        if(Session::has('note')) Session::forget('note');

        return redirect('/');

    }

    /**
     * 激活
     *
     */
    public function fail()
    {
        $color = 'warning';
        $icon = 'bell-o';
        $text = '您填写的链接有错误!请检查,若需要帮助请联系管理员';

        return view('note', compact('color', 'icon', 'text'));
    }






}



















