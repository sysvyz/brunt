<?php
/**
 * Created by PhpStorm.
 * User: mb
 * Date: 07.05.16
 * Time: 03:39
 */

namespace Brunt;

interface InjectableInterface{
    public static function _DI_DEPENDENCIES();
    public static function _DI_PROVIDERS();
}