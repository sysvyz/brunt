<?php
namespace Brunt\Provider {

    use Brunt\Injector;
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

        /**
         * @param string $class
         * @param callable $callable
         * @return ClassFactoryProvider
         */
        public static function init(string $class,callable $callable)
        {
            return new self($class,$callable);
        }
        public function __construct(string $class,callable $callable)
        {
            parent::__construct($callable);

            $this->reflector = new Reflector($class);
            $this->class = $class;

        }

        public function lazy(){
            return new LazyClassProvider($this);
        }

        public function singleton(){
            return new SingletonClassProvider($this);
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