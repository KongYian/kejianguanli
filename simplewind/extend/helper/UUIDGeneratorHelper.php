<?php
/**
 * Created by PhpStorm.
 * User: blue
 * Date: 2018/5/15
 * Time: 7:18
 */

namespace helper;


class UUIDGeneratorHelper
{
    public static function gen()
    {
        mt_srand((double)microtime() * 10000);//optional for php 4.2.0 and up.
        $charid = md5(uniqid(rand(), true));
        $hyphen = '';
        $uuid = ''
            . substr($charid, 0, 8) . $hyphen
            . substr($charid, 8, 4) . $hyphen
            . substr($charid, 12, 4) . $hyphen
            . substr($charid, 16, 4) . $hyphen
            . substr($charid, 20, 12)
            . '';
        return $uuid;
    }

    public static function genNumber(){
        return rand(1000,9999);
    }
}