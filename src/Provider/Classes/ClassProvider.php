<?php


namespace Brunt\Provider\Classes {

    use Brunt\Exception\CircularDependencyException;
    use Brunt\Injector;
    use Brunt\Provider\AbstractProvider;
    use Brunt\Provider\I\ClassProviderInterface;
    use Brunt\Provider\Lazy\LazyClassProvider;
    use Brunt\Provider\Singleton\SingletonClassProvider;
    use Brunt\Reflection\CR\CRParam;
    use Brunt\Reflection\Reflector;
    use Brunt\Reflection\ReflectorFactory;


    class ClassProvider extends AbstractProvider implements ClassProviderInterface
    {


        /**
         * @var ClassProvider[]
         */
        protected static $INSTANCE_MAPPING = [];
        /**
         * @var ClassProviderInterface[]
         */
        protected static $PROVIDER_MAPPING = [];
        /**
         * @var ClassProviderInterface[]
         */
        protected static $DEPENDENCY_MAPPING = [];

        /**
         * @var Reflector
         */
        protected $reflector;

        /**
         * @var string
         */
        protected $class;

        /**
         * ClassProvider constructor.
         * @param string $class
         */
        public function __construct($class)
        {

            $this->reflector = ReflectorFactory::buildReflectorByClassName($class);
            $this->class = $class;

            //todo disable for production?
            self::validate($this->reflector);
        }

        /**
         *
         * convenience function wrapper for constructor
         *
         * @param $class
         * @return ClassProvider
         */
        public static function init($class)
        {
            if (!isset(self::$INSTANCE_MAPPING[$class])) {
                self::$INSTANCE_MAPPING[$class] = new self($class);
            }

            return self::$INSTANCE_MAPPING[$class];
        }

        /**
         * detect circular dependencies -> maybe not in production?
         * if A depends on B and B on A then the injector cant resolve this by himself.
         * dependencies MUST be A DAG https://en.wikipedia.org/wiki/Directed_acyclic_graph
         * Validator uses depth-first-search to find loops - there are better ways.
         *
         * todo research: claim: it could be possible to have circular dependencies with bounds or inheritance if they converge somehow
         * circular dependencies should be avoided or if REALLY (recursive structures i.e. lists, trees, graphs) necessary build it with FactoryProviders
         * http://misko.hevery.com/2008/08/01/circular-dependency-in-constructors-and-dependency-injection/
         *
         * @param Reflector $reflector
         * @param array $path
         */
        private static function validate(Reflector $reflector, $path = [])
        {
            //get class name
            $className = $reflector->getClassName();
            if ($className == Injector::class) {
                return;
            }
            if (in_array($className, $path)) {
                throw new CircularDependencyException ($className . ' must not depend on it self');
            }
            array_push($path, $reflector->getClassName());

            /** @var CRParam $dependency */
            foreach ($reflector->getConstructorParams() as $dependency) {
                if ($dependency->hasType() && !$dependency->isBuiltin()) {
                    $nextReflector = ReflectorFactory::buildReflectorByClassName($dependency->getType() . '');
                    self::validate($nextReflector, $path);
                }
            }
        }

        /**
         * Read Dependencies And Providers
         * Make a new (Child)Injector for the requested Object with Providers
         * Build Dependencies recursively
         * Build requested Object
         *
         * @param Injector $injector
         * @return mixed
         */
        function get(Injector $injector)
        {
            $className = $this->reflector->getClassName();
            $dependencies = $this->_getDependencies($className);
            //      print_r($dependencies);
            if (!empty($dependencies)) {

                $childInjector = $injector->getChild($this->_getProviders($className));

                //recursive build dependencies
                $params = (array_map(function ($dependency) use ($childInjector) {
                    return $childInjector->get($dependency['token']);
                }, $dependencies));

            } else {
                $params = [];
            }
            return new $className(... array_values($params));
        }

        /**
         * @param $className
         * @return array|ClassProviderInterface
         */
        protected function _getDependencies($className)
        {
            if (isset(self::$DEPENDENCY_MAPPING[$className])) {
                $dependencies = self::$DEPENDENCY_MAPPING[$className];
                return $dependencies;
            } else {
                $dependencies = $this->reflector->resolveDependencies($this->reflector->getConstructorParams());
                self::$DEPENDENCY_MAPPING[$className] = $dependencies;
                return $dependencies;
            }
        }

        /**
         * @param $className
         * @return array|ClassProviderInterface
         */
        protected function _getProviders($className)
        {
            if (isset(self::$PROVIDER_MAPPING[$className])) {
                $providers = self::$PROVIDER_MAPPING[$className];
                return $providers;
            } else {
                $providers = $this->reflector->getProviders();
                self::$PROVIDER_MAPPING[$className] = $providers;
                return $providers;
            }
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

        public function lazy()
        {
            return new LazyClassProvider($this);
        }

        public function singleton()
        {
            return new SingletonClassProvider($this);
        }

    }
}
