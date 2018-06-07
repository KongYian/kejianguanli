<?php
/**
 * Created by PhpStorm.
 * User: blue
 * Date: 2018/5/15
 * Time: 11:22
 */

namespace helper;


class ArrayHelper
{
    public static function getValue($array,$key,$default = ''){
        if (is_array($array) && array_key_exists($key, $array)) {
            return $array[$key];
        }
        if (is_object($array)) {
            return $array->$key;
        } elseif (is_array($array)) {
            return array_key_exists($key, $array) ? $array[$key] : $default;
        } else {
            return $default;
        }
    }
}