<?php


namespace Brunt\Provider {


    use Brunt\Injector;

    class ValueProvider  extends ConcreteProvider
    {

        private $value;


        /**
         * VariableProvider constructor.
         */
        public function __construct($value)
        {
            $this->value = $value;
        }

        function __invoke(Injector $injector)
        {
            return $this->value;
        }

        public static function init($value){
            return self($value);
        }
    }
}