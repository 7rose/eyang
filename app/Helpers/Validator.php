<?php

namespace App\Helpers;

use App\Order;

/**
 * 校验 
 *
 */
class Validator
{
    
    // 18位身份证
    public function checkIdNumber($val)
    {
        $rule='/^\d{6}(18|19|20)?\d{2}(0[1-9]|1[012])(0[1-9]|[12]\d|3[01])\d{3}(\d|[xX])$/';
        return preg_match($rule,$val);
    }

    // 手机号
    public function checkMobile($val)
    {
        $rule = '/^1[3456789]{1}\d{9}$/';
        return preg_match($rule,$val);
    }

    /**
     * 报备
     *
     */
    public function bb($order_id)
    {
        $order = Order::findOrFail($order_id);

        switch ($order->product->org->code) {
            case 'rzd':
                // 融知道
                switch ($order->product->type->val) {
                    // 类型
                    case 'cps':
                        return ['app'=>'下款图', 'video'=>'下款视频: 必须有app内个人中心和待还款页面'];
                        break;
                    case 'cpa':
                        return ['app'=>'下款图', 'sms'=>'到账短信'];
                        break;
                    
                    default:
                        # code...
                        break;
                }
                break;

            case 'jyh':
                # code...
                break;
            
            default:
                # code...
                break;
        }

        return false;
    }

    /**
     * 替换表单
     *
     */
    public function bbBackForm($order_id)
    {
        $items = $this->bb($order_id);
        if(!$items) return false;

        return array_key_exists('video', $items);
    }

}
















