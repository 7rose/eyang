<?php

namespace App\Forms;

use Kris\LaravelFormBuilder\Form;

class LoginForm extends Form
{
    public function buildForm()
    {
        $this
        ->add('mobile', 'number', [
                'label' => "手机号",
                'rules' => 'required',
            ])
        ->add('password', 'password', [
                'label' => "密码",
                'rules' => 'required|min:6|max:32',
            ])
        ->add('remember_me', 'checkbox', [
                'value' => 1,
                'label' => "记住登录",
                'checked' => false
            ])
        ->add('submit','submit',[
            'label' => '确定',
            'attr' => ['class' => 'btn btn-primary btn-block']
        ]);
    }
}
