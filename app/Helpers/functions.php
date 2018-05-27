<?php
/**
 * Created by PhpStorm.
 * User: Wongluyu
 * Date: 2018/5/26
 * Time: 2:47
 */

function randomString($length = 6)
{
    $str = '';
    $pattern = '1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLOMNOPQRSTUVWXYZ';
    for ($i = 0; $i < $length; $i++) {
        $str .= $pattern[mt_rand(0, strlen($pattern) - 1)];
    }
    return $str;
}