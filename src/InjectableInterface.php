<?php

namespace Brunt {

    interface InjectableInterface{
        public static function _DI_DEPENDENCIES();
        public static function _DI_PROVIDERS();
    }
}