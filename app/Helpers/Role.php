<?php

namespace App\Helpers;

use Auth;

use App\User;
use App\Org;
use App\Shop;
use App\Helpers\Info;


/**
 * 授权 
 *
 */
class Role
{

    // show
    public function show($json, $key) 
    {
        try {
            $arr = json_decode($json);
            return $arr && array_key_exists($key, $arr) ? $arr->$key : null;
        } catch (Exception $e) {
            return null;
            exit();
        }
    }

    // 存在并且为为true
    private function hasAndTrue($json, $key) 
    {
        $arr = json_decode($json);
        return $arr && array_key_exists($key, $arr) && $arr->$key == true ? true : false;
    }

    // 选择目标
    private function choose($id=0)
    {
        if (!Auth::check() && $id==0) return false;
        return $id == 0 ? Auth::user() : User::findOrFail($id);
    }

    /**
     * 账号锁定
     *
     */
    public function locked($id=0)
    {
        return $this->hasAndTrue($this->choose($id)->auth, 'locked');
    }


    /**
     * root : 超级管理员
     *
     */
    public function root($id=0)
    {
        if(!$this->choose()) return false;
        return $this->hasAndTrue($this->choose($id)->auth, 'root');
    }

    /**
     * admin : 管理员
     *
     */
    public function admin($id=0)
    {
        if(!$this->choose()) return false;

        if($this->root($id)) return true;
        return $this->hasAndTrue($this->choose($id)->auth, 'admin');
    }

    /**
     * 经理
     *
     */
    public function manager($id=0)
    {
        if(!$this->choose()) return false;

        if($this->admin($id)) return true;
        return $this->hasAndTrue($this->choose($id)->auth, 'manager');
    }


    /**
     * 产品发布人
     *
     */
    public function issuer($id=0)
    {
        if(!$this->choose()) return false;

        if($this->admin($id)) return true;
        return $this->hasAndTrue($this->choose($id)->auth, 'issuer');
    }

    /**
     * admin : 老板
     *
     */
    public function boss($id=0)
    {
        if(!$this->choose()) return false;

        if($this->admin($id)) return true;
        return $this->hasAndTrue($this->choose($id)->auth, 'boss');
    }


    /**
     * self : 自己
     *
     */
    public function self($id)
    {
        return Auth::id() == $id;
    }

    /**
     * 
     *
     */
    public function sameShop($id)
    {
        $target = User::findOrFail($id);
        return Auth::user()->shop_id == $target->shop_id;
    }

    /**
     * grater than : 有权
     *
     */
    public function gt($id)
    {
        if($this->root() && !$this->root($id)) return true;
        if($this->admin() && !$this->admin($id)) return true;
        if($this->manager() && !$this->manager($id)) return true;
        if($this->boss() && !$this->manager($id) && !$this->boss($id) && $this->sameShop($id) && !$this->self($id)) return true;

        return false;
    }

    /**
     * 店主
     *
     */
    public function shopBoss($shop_id=0)
    {
        $info = new Info;

        if($shop_id == 0) $shop_id = $info->id();
        if(Auth::user()->shop_id == $shop_id && $this->boss()) return true;

        $shop = Shop::findOrFail($shop_id);
        if(Auth::user()->shop_id == $shop->upper->id && $this->boss()) return true;

        return false;
    }

}
















