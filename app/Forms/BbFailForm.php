<?php

namespace App\Forms;

use Kris\LaravelFormBuilder\Form;

class BbFailForm extends Form
{
    public function buildForm()
    {
        $this
        ->add('fail', 'file', [
                'label' => "未下款截图",
                'rules' => 'required',
            ])
        
        ->add('submit','submit',[
            'label' => '确定',
            'attr' => ['class' => 'btn btn-primary btn-block']
        ]);
    }
}
