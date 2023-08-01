<?php

namespace Metracore\Helper;

use function is_null;
use function str_replace;
use function ucwords;
use function lcfirst;
use function is_string;
use function strval;
use function strtolower;
use function mb_substr;

/**
 * String class for nitrovel framework helper component
 */
class Str{
    

    public static function fix($val) : string {
        return is_null($val) ? '' : ( is_string($val) ? $val : strval($val) );
    }


    /*
    |   String substraction helper function
    |   Return part of a string
    |
    |   @param string $val
    |   @param int $s
    |   @param int|null $l
    |   @return string
    */
    public static function substr($val, $s, $l = null){
        return mb_substr($val, $s, $l, 'UTF-8');
    }


    /*
    |   Convert a string to kebab case
    |   eg : ThePascalCaseShouldLooksLikeThis
    |
    |   @param string $val
    |   @return string
    */
    public static function pascal($val) : string {
        return str_replace(' ','', ucwords( static::_caseChange($val) ) );
    }


    /*
    |   Convert a string to studly case
    |   studly is also known as pascal case
    |
    |   @param string $val
    |   @return string
    */
    public static function studly($val) : string {
        return static::pascal($val);
    }


    /*
    |   Convert a string to snake case
    |   eg : the_snake_case_should_looks_like_this
    |
    |   @param string $val
    |   @return string
    */
    public static function snake($val) : string {
        return str_replace(' ','_', static::_caseChange($val) );
    }


    /*
    |   Convert a string to camel case
    |   eg : theCamelCaseShouldLooksLikeThis
    |
    |   @param string $val
    |   @return string
    */
    public static function camel($val) : string {
        return lcfirst( static::pascal($val) );
    }


    /*
    |   Convert a string to kebab case
    |   eg : the-kebab-case-should-looks-like-this
    |
    |   @param string $val
    |   @return string
    */
    public static function kebab($val) : string {
        return str_replace(' ','-', static::_caseChange($val) );
    }


    /*
    | Private function used in case change functions like camel,snake,kebab etc
    |
    */
    private static function _caseChange($val) : string {
        return strtolower( str_replace('.','',str_replace(['_','-'],' ',$val)) );
    }


}


?>
