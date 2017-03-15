<?php
namespace Brunt\Provider\Classes {

    use Brunt\Provider\FactoryProvider;
    use Brunt\Provider\Contract\ClassProviderInterface;
    use Brunt\Provider\Lazy\LazyClassProvider;
    use Brunt\Provider\Singleton\SingletonClassProvider;
    use Brunt\Reflection\Reflector;
    use Brunt\Reflection\ReflectorFactory;


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

            $this->reflector = ReflectorFactory::buildReflectorByClassName($class);
            $this->class = $class;

        }

        public function lazy(){
            return new LazyClassProvider($this);
        }

        /**
         * @return SingletonClassProvider
         */
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