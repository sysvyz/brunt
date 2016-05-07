<?php
namespace Brunt\Provider {
    use Brunt\Injector;

    /**
     * Created by PhpStorm.
     * User: mb
     * Date: 17.04.16
     * Time: 01:25
     */
    class FactoryProvider implements Provider{

        private $callable = [];

        /**
         * DIProvider constructor.
         * @param $callable
         */
        public function __construct(callable $callable)
        {
            $this->callable[0] = $callable;
        }

        function __invoke(Injector $injector)
        {
            return $this->callable[0]($injector);
        }

        public static function init(callable $callable)
        {
            return new static($callable);
        }

    }
}