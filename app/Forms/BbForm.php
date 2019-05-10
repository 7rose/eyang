<?php

namespace App\Forms;

use Kris\LaravelFormBuilder\Form;

use App\Helpers\Filter;
use App\Order;

class BbForm extends Form
{
    public function buildForm()
    {
        $f = new Filter;
        $record = Order::findOrFail($this->getData('id'));
        $product_id = $record->product->id;

        $items = $f->bb($product_id);

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
