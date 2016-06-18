<?php


namespace Brunt\Provider {


    use Brunt\Injector;

    class ValueProvider  extends AbstractProvider
    {
        /**
         * @var mixed
         */
        private $value;


        /**
         * VariableProvider constructor.
         */
        public function __construct($value)
        {
            $this->value = $value;
        }

        /**
         * @param Injector $injector
         * @return mixed
         */
        function get(Injector $injector)
        {
            return $this->value;
        }

        /**
         * @param $value
         * @return ValueProvider
         */
        public static function init($value){
            return new self($value);
        }

        /**
         * @return $this
         */
        public function lazy(){
            return $this;
        }
    }
}