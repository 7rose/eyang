<?php

namespace App\Forms;

use Kris\LaravelFormBuilder\Form;

use App\Org;
use App\Conf;

class ProductForm extends Form
{
    public function buildForm()
    {
        $org_array = Org::all()->pluck('name', 'id')->toArray();

        $type_array = Conf::where('key', 'product_type')->pluck('val', 'id')->toArray();

        $this
        ->add('org_id', 'select', [
                'label' => '供应商',
                'empty_value' => '--- 选择 ---',
                'choices' => $org_array,
                'rules' => 'required',
            ])
        ->add('type_id', 'select', [
                'label' => '产品类型',
                'empty_value' => '--- 选择 ---',
                'choices' => $type_array,
                'rules' => 'required',
            ])
        ->add('name', 'text', [
                'label' => "产品名",
                'rules' => 'required|min:2|max:8',
            ])
        ->add('quota', 'number', [
                'label' => "额度",
                'default_value' => "10000",
                'rules' => 'min:300|max:1000000',
            ])

        ->add('url', 'url', [
                'label' => "链接",
                'rules' => 'required|min:6|max:50',
            ])
        ->add('zm', 'number', [
                'label' => "芝麻分要求",
                'default_value' => "550",
                'rules' => 'min:300|max:1000',
            ])
        ->add('fs', 'checkbox', [
                'value' => 1,
                'label' => "正在放水!",
                'checked' => false
            ])
        ->add('content', 'textarea', [
                'label' => "备注",
                'rules' => 'min:6|max:200',
            ])
        ->add('submit','submit',[
            'label' => '下一步',
            'attr' => ['class' => 'btn btn-primary btn-block']
        ]);
    }
}
