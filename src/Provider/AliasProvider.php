<?php


namespace Brunt\Provider {


    use Brunt\Injector;
    use Brunt\Provider\I\ProviderInterface;

    class AliasProvider  implements ProviderInterface
    {
        /**
         * @var mixed
         */
        private $alias;


        /**
         * VariableProvider constructor.
         */
        public function __construct($alias)
        {
            $this->alias = $alias;
        }

        /**
         * @param Injector $injector
         * @return mixed
         */
        function __invoke(Injector $injector)
        {
            return $injector->get($this->alias);
        }

        /**
         * @param $value
         * @return ValueProvider
         */
        public static function init($alias){
            return new self($alias);
        }

        /**
         * @return $this
         */
        public function lazy(){
            return $this;
        }

        /**
         * @return $this
         */
        public function singleton()
        {
            return $this;
        }
    }
}