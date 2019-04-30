<?php

namespace App\Helpers;

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

}