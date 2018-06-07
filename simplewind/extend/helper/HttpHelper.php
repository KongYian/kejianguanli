<?php

namespace helper;
use think\Request;

/**
 * Created by PhpStorm.
 * User: blue
 * Date: 2018/5/15
 * Time: 6:26
 */
class HttpHelper
{
    public static function postOrget($key,$default = ''){
        $val = Request::instance()->post($key,$default);
        if($val == null){
            $val = Request::instance()->get($key,$default);
        }
        return $val !== null ? self::trimall($val) : $default;
    }

    public static function trimall($str)//删除空格
    {
        $oldchar=array(" ","　","\t","\n","\r");
        $newchar=array("","","","","");
        return
            str_replace($oldchar,$newchar,$str);
    }
}