<?php
/**
 * Created by PhpStorm.
 * User: Wongluyu
 * Date: 2018/6/9
 * Time: 17:00
 */

namespace App\Http\Controllers\Helpers;


class helper
{
    public static function byteToMB($int){
        return number_format($int/(1024*1024),2);
    }

    public static function GBToByte($int){
        return $int*1024*1024*1024;
    }

    public static function flowToMoney($flow){
        return $flow;
    }
}