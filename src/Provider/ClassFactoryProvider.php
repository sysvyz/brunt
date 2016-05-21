<?php
namespace Brunt\Provider {

    use Brunt\Provider\I\ClassProvider as ClassProviderInterface;
    use Brunt\Reflection\Reflector;


    class ClassFactoryProvider extends FactoryProvider implements ClassProviderInterface{

        /**
         * @var Reflector
         */
        protected $reflector;

        /**
         * @var string
         */
        protected $class;
        
        public function __construct(callable $callable,string $class)
        {

            parent::__construct($callable);
            $this->reflector = new Reflector($class);
            $this->class = $class;

        }

        public function lazy(){
            return new LazyClassProvider($this);
        }

        /**
         * @return string
         */
        public function getClass()
        {
            return $this->class;
        }

        /**
         * @return Reflector
         */
        public function getReflector()
        {
            return $this->reflector;
        }
    }
}