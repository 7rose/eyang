<?php

namespace App\Forms;

use Kris\LaravelFormBuilder\Form;

use App\Org;
use App\Helpers\Info;
use App\Helpers\Role;

class ShopActiveForm extends Form
{
    public function buildForm()
    {
        $info = new Info;
        $role = new Role;

        if(!$role->shopBoss($info->id()) || !count($info->lackOrgIds())) return redirect('/');
        
        $orgs = Org::whereIn('id', $info->lackOrgIds())->get();

        foreach ($orgs as $org) {
            $this
            ->add($org->id, 'url', [
                'label' => $org->name,
                'rules' => 'min:2|max:200',
            ]);
        }

        $this
        // ->add('id', 'hidden', [
        //     'value' => $info->id(),
        // ])
        ->add('submit','submit',[
            'label' => '确定',
            'attr' => ['class' => 'btn btn-primary btn-block']
        ]);
    }
}
