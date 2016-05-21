<?php

namespace Brunt\Provider {


    use Brunt\Injector;

    interface  Provider
    {
        function __invoke(Injector $injector);

        /**
         * @return SingletonProvider
         */
        public function singleton();
        public function lazy();
    }
}
