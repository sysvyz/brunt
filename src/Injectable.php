<?php


namespace Brunt {

    abstract class Injectable implements InjectableInterface
    {
        public static function _DI_DEPENDENCIES(){
            return [];
        }
        public static function _DI_PROVIDERS(){
            return [];
        }
    }
}