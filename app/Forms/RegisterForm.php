<?php

namespace App\Forms;

use Kris\LaravelFormBuilder\Form;

class RegisterForm extends Form
{
    public function buildForm()
    {


        $this
        ->add('name', 'text', [
                'label' => "姓名",
                'rules' => 'required|min:2|max:10',
            ])
        ->add('mobile', 'number', [
                'label' => "手机号: 需与贷款手机号一致",
                'rules' => 'required',
            ])
        ->add('password', 'password', [
                'label' => "密码",
                'rules' => 'required|min:6|max:32',
            ])
        ->add('password_confirmed', 'password', [
                'label' => "确认密码",
                'rules' => 'required|min:6|max:32',
            ])
        ->add('submit','submit',[
            'label' => '确定',
            'attr' => ['class' => 'btn btn-primary btn-block']
        ]);
    }
}
