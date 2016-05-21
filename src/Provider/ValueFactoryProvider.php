<?php
namespace Brunt\Provider {

    use Brunt\Injector;

    class ValueFactoryProvider extends FactoryProvider
    {
        

        /**
         * @param callable $callable
         * @return FactoryProvider
         */
        public static function init(callable $callable)
        {
            return new self($callable);
        }



    }
}