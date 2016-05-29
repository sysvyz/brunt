<?php


namespace Brunt\Provider\Classes {

    use Brunt\Exception\CircularDependencyException;
    use Brunt\Injector;
    use Brunt\Provider\ConcreteProvider;
    use Brunt\Provider\Lazy\LazyClassProvider;
    use Brunt\Provider\Singleton\SingletonClassProvider;
    use Brunt\Reflection\Reflector;
    use Brunt\Provider\I\ClassProviderInterface ;


    class ClassProvider extends ConcreteProvider implements ClassProviderInterface
    {
        /**
         * @var Reflector
         */
        protected $reflector;

        /**
         * @var string
         */
        protected $class;


        /**
         *
         * convenience function wrapper for constructor
         *
         * @param $class
         * @return ClassProvider
         */
        public static function init($class)
        {
            return new self($class);
        }
        /**
         * ClassProvider constructor.
         * @param string $class
         * @param bool $singleton
         */
        public function __construct($class)
        {
            $this->reflector = new Reflector($class);
            $this->class = $class;

            //todo disable for production?
            self::validate($this->reflector);
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
            foreach ($reflector->getConstructorParams() as $dependency) {
                if ($dependency->getType() && !$dependency->getType()->isBuiltin()) {
                    $nextReflector = new Reflector($dependency->getType() . '');
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
        function __invoke(Injector $injector)
        {

            $providers = $this->reflector->getProviders();
            $childInjector = $injector->getChild($providers);

            $dependencies = $this->reflector->resolveDependencies($this->reflector->getConstructorParams());
            //recursive build dependencies
            $params = (array_map(function ($dependency) use ($childInjector) {
                return $childInjector->get($dependency['token']);
            }, $dependencies));

            $className = $this->reflector->getClassName();
            return new $className(...$params);
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

        public function lazy(){
            return new LazyClassProvider($this);
        }
        public function singleton(){
            return new SingletonClassProvider($this);
        }
        
    }
}
