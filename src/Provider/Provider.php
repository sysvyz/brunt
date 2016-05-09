<?php
/**
 * Created by PhpStorm.
 * User: mb
 * Date: 17.04.16
 * Time: 01:28
 */

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
