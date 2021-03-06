<?php

namespace Brunt\Provider\Contract {


    use Brunt\Injector;

    interface  ProviderInterface
    {
//        function __invoke(Injector $injector);
        function get(Injector $injector);

        /**
         * @return SingletonProviderInterface
         */
        public function singleton();
        public function lazy();
    }
}
