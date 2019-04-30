<?php

namespace App\Forms;

use Kris\LaravelFormBuilder\Form;

class PasswordForm extends Form
{
    public function buildForm()
    {
        $this
        ->add('password', 'password', [
                'label' => "密码",
                'rules' => 'required|min:6|max:32',
            ])
        ->add('password_confirmed', 'password', [
                'label' => "确认密码",
                'rules' => 'required|min:6|max:32',
            ])
        ->add('submit','submit',[
            'label' => '更新密码',
            'attr' => ['class' => 'btn btn-primary btn-block']
        ]);
    }
}
