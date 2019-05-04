<?php

namespace App\Forms;

use Kris\LaravelFormBuilder\Form;

use Auth;

class OrderForm extends Form
{
    public function buildForm()
    {
        // $my_shop = Auth::user()->shop;

        // $subs = $my_shop->subs->pluck('domain', 'id')->toArray();

        // $list = array_add($subs, $my_shop->id, $my_shop->domain);

        $this
        ->add('mobile', 'number', [
                'label' => "客户手机号",
                'rules' => 'required',
            ])
        ->add('name', 'text', [
                'label' => "产品名称",
                'rules' => 'required|min:2|max:10',
            ])
        ->add('amount', 'number', [
                'label' => "金额(可不填)",
                'rules' => 'min:100|max:100000',
            ])
         // ->add('shop_id', 'select', [
         //        'label' => "店",
         //        'empty_value' => '-- 选择 --',
         //        'choices' => $list,
         //        'rules' => 'required',
         //    ])
        ->add('submit','submit',[
            'label' => '确定',
            'attr' => ['class' => 'btn btn-primary btn-block']
        ]);
    }
}


