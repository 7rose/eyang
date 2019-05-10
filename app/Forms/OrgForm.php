<?php

namespace App\Forms;

use Kris\LaravelFormBuilder\Form;

use App\Forms\OrgForm;
use App\Conf;

class OrgForm extends Form
{
    public function buildForm()
    {
        $type_array = Conf::where('key', 'product_type')->pluck('text', 'val')->toArray();

        $this
        ->add('name', 'text', [
            'label' => "名称",
            'rules' => 'required',
        ])
        ->add('code', 'text', [
            'label' => "编码",
            'rules' => 'required|min:3|max:10',
        ])
        ->add('templet', 'text', [
            'label' => "链接模板",
            'attr' => ['placeholder' => 'http://shmuchun.cn//loan/url/{product}/{shop}'],
            'rules' => 'required|min:3|max:200',
        ])
        
        ->add('shop', 'text', [
            'label' => "店编码",
            'attr' => ['placeholder' => 'w{7}'],
            'rules' => 'required|min:2|max:100',
        ])
        ->add('product', 'text', [
            'label' => "产品编码",
            'attr' => ['placeholder' => 'd{4,}'],
            'rules' => 'required|min:3|max:100',
        ]);

        foreach ($type_array as $key => $value) {
            $this
            ->add($key, 'text', [
                'label' => $key.': 报备字段',
                'attr' => ['placeholder' => '{"app":"下款图", "sms":"到账短信"}'],
                'rules' => 'min:3|max:200',
            ]);
        }

        $this
        ->add('submit','submit',[
            'label' => '确定',
            'attr' => ['class' => 'btn btn-primary btn-block']
        ]);
    }
}
