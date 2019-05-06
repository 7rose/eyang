<?php

namespace App\Http\Controllers;

use Kris\LaravelFormBuilder\FormBuilderTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Session;

use App\Shop;
use App\Forms\ShopActiveForm;
use App\Forms\ShopForm;
use App\Helpers\Link;
use App\Helpers\Info;
use App\Helpers\Role;

class ShopController extends Controller
{
    use FormBuilderTrait;

    /**
     * 列表
     *
     */
    public function index(Info $info, Role $role)
    {
        if(!$role->admin() && !$role->shopBoss()) abort(403);

        $record = $info->shop;

        return view('shops.shops', compact('record'));
    }

    /**
     * 激活表单
     *
     */
    public function active(Role $role)
    {
        if(!$role->admin() && !$role->shopBoss()) abort(403);

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
    public function doActive(Request $request, Link $link, Role $role)
    {
        if(!$role->admin() && !$role->shopBoss()) abort(403);
        
        $all = $request->all();
        Arr::forget($all, '_token');

        foreach ($all as $org_id => $url) {
            if(trim($url)) {
                try {
                    
                    $resault = $link->saveShopCode(intval($org_id), $url);
                } catch (Exception $e) {
                    abort('403');
                }
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


    /**
     * 新建
     *
     */
    public function create($parent_id, Role $role)
    {
        if(!$role->admin()) abort(403);

         $form = $this->form(ShopForm::class, [
            'method' => 'POST',
            'url' => '/shops/store/'.$parent_id
        ]);

        $title = '新店';
        $icon = 'bank';

        return view('form', compact('form','title','icon'));
    }

    /**
     * 保存
     *
     */
    public function store($parent_id, Role $role, Request $request)
    {
        if(!$role->admin()) abort(403);

        $info = [
            'name' => $request->name,
            'full_name' => $request->full_name,
            'color' => $request->color,
            'wechat' => $request->wechat,
        ];

        $parent = Shop::findOrFail($parent_id);
        $level = $parent->level + 1;

        if($request->limit && $level == 1) $info['limit'] = $request->limit;

        $new = [
            'parent_id' => $parent_id,
            'level' => $level,
            'domain' => $request->domain,
            'info' => json_encode($info)
        ];

        Shop::create($new);

        $color = 'success';
        $icon = 'check-square-o';
        $text = '店新建成功!';

        return view('note', compact('color', 'icon', 'text'));

    }





}



















