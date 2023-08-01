<?php

namespace Metracore\Helper;

use ArrayAccess;
use InvalidArgumentException;


use function count;
use function is_null;
use function is_array;
use function array_rand;
use function array_unshift;
use function array_key_exists;
use function func_num_args;

/*
* Array class for nitrovel framework helper component
* Development Date : Aug 1, 2023
*
*/
class Arr{

    public static function isArr($v) : bool {
        return is_array($v) || $v instanceof ArrayAccess;
    }

    
    public static function hasKey($arr, $key) {
        return $arr instanceof ArrayAccess ? $arr->offsetExists($key) : array_key_exists($key,$arr);
    }


    public static function merge($arr, $val, $key = null) : array {
        return func_num_args() === 2 ? [...$arr,$val] : [...$arr, [$key => $val] ];
    }


    public static function merge_pre($arr, $val, $key = null) : array {
        return func_num_args() === 2 ? array_unshift($arr,$val) : [$key=>$val] + $arr;
    }


    public static function rand(array $arr, int $count = null, bool $preserveKeys = false) : array {

        if( $count <= 0 ):
            return [];
        endif;

        $count = is_null($count) ? 1 : $count;

        if( count($arr) < $count ):
            throw new InvalidArgumentException("Array size is "+count($arr)+". Which is less then the requested count.");
        endif;

        $randKeys = array_rand($arr, $count);

        $output = [];

        foreach($randKeys as $k){
            if($preserveKeys):
                $output[$k] = $arr[$k];
            else:
                $output[] = $arr[$k];
            endif;
        }

    return $output;
    }


    public static function combine($keys, $vals) : array {

        if( count($keys) !== count($vals) ):
            throw new InvalidArgumentException("The size of keys should be equal to size of values");
        endif;

        return array_combine($keys, $vals);
    }


    public static function httpQuery($arr) : array {
        return http_build_query($array, '', '&', PHP_QUERY_RFC3986);
    }


    public static function fix($val) : array {
        return is_null($val) ? [] : ( is_array($val) ? $val : [$val] );
    }

}


?>
