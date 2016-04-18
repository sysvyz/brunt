<?php
/**
 * Created by PhpStorm.
 * User: mb
 * Date: 17.04.16
 * Time: 01:46
 */

namespace SVZ;

interface InjectableInterface{
    public static function _DI_DEPENDENCIES();
    public static function _DI_PROVIDERS();
}

abstract class Injectable implements InjectableInterface
{
    public static function _DI_DEPENDENCIES(){
        return [];
    }
    public static function _DI_PROVIDERS(){
        return [];
    }
}