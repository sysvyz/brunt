<?php
namespace Brunt\Provider {

    use Brunt\Injector;

    /**
     * Created by PhpStorm.
     * User: mb
     * Date: 17.04.16
     * Time: 01:25
     */
    class FactoryProvider  extends ConcreteProvider{

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
        function __invoke(Injector $injector)
        {
            return $this->callable[0]($injector);
        }

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