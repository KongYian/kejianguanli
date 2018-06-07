<?php

namespace helper;

/**
 * Created by PhpStorm.
 * User: blue
 * Date: 2018/5/24
 * Time: 1:03
 */
class CommonHelper
{
    public static function blue($msg='',$desc='',$filepath = 'E:\phpstudy\WWW\mylog'){
        $start = '['.date('H:i:s').'] ';
        if(is_array($msg) || is_object($msg)){
            $out = str_replace(':','=>',json_encode($msg,JSON_UNESCAPED_UNICODE));
            $out = str_replace('{','[',$out);
            $out = str_replace('}',']',$out);
            error_log($start.$desc.'--->'.$out.PHP_EOL,3,$filepath);
        }else{
            error_log($start.$desc.'--->'.$msg.PHP_EOL,3,$filepath);
        }
    }
}