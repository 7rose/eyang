<?php

namespace App\Forms;

use Kris\LaravelFormBuilder\Form;

class ShopForm extends Form
{
    public function buildForm()
    {
        $this
        ->add('domain', 'text', [
                'label' => "域名",
                'rules' => 'required|min:6|max:16',
            ])
        ->add('limit', 'number', [
                'label' => "分店限制(非分店无效)",
                'rules' => 'min:1|max:50',
            ])
        ->add('name', 'text', [
                'label' => "简称",
                'rules' => 'required|min:1|max:6',
            ])
        ->add('full_name', 'text', [
                'label' => "店名全名",
                'rules' => 'required|min:2|max:10',
            ])
        ->add('color', 'select', [
                'label' => "配色",
                'choices' => ['blue' => '蓝', 'green' => '绿','sea' => '海绿', 'red' => '红', 'orange'=>'橙'],
                'rules' => 'required',
            ])
        ->add('wechat', 'url', [
                'label' => "微信",
                'rules' => 'required|min:10|max:100',
            ])
        
        ->add('submit','submit',[
            'label' => '确定',
            'attr' => ['class' => 'btn btn-primary btn-block']
        ]);
    }
}
