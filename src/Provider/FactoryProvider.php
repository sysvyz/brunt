<?php
namespace Brunt\Provider {

    use Brunt\Injector;

    abstract class FactoryProvider extends ConcreteProvider
    {

        private $callable = [];

        /**
         * FactoryProvider constructor.
         * @param $callable
         */
        public function __construct(callable $callable)
        {
            $this->callable[0] = $callable;
        }

      

        /**
         * @param Injector $injector
         * @return mixed
         */
        function get(Injector $injector)
        {
            return $this->callable[0]($injector);
        }

    }
}