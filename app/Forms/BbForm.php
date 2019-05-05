<?php

namespace App\Forms;

use Kris\LaravelFormBuilder\Form;

use App\Helpers\Validator;

class BbForm extends Form
{
    public function buildForm()
    {
        $v = new Validator;

        $items = $v->bb($this->getData('order_id'));
        if(!$items) abort('403');

        foreach ($items as $key => $value) {

            $this->add($key, 'file', [
                'label' => $value,
                'rules' => 'required',
            ]);
        }

        $this 
        ->add('submit','submit',[
            'label' => '确定提交',
            'attr' => ['class' => 'btn btn-primary btn-block']
        ]);
    }
}
