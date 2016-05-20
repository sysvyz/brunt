<?php
/**
 * Created by PhpStorm.
 * User: mb
 * Date: 17.04.16
 * Time: 01:28
 */

namespace Brunt\Provider {

    use Brunt\Exception\CircularDependencyException;
    use Brunt\Injector;
    use Brunt\Reflection\Reflector;


    class ClassProvider extends ConcreteProvider
    {
        /**
         * @var Reflector
         */
        private $reflector;

        /**
         * @var string
         */
        private $class;


        /**
         *
         * convenience function wrapper for constructor
         *
         * @param $class
         * @param bool $singleton
         * @return ClassProvider
         */
        public static function init($class, $singleton = true)
        {
            return new self($class, $singleton);
        }
        /**
         * ClassProvider constructor.
         * @param string $class
         * @param bool $singleton
         */
        public function __construct($class, $singleton = true)
        {
            $this->reflector = new Reflector($class);
            $this->singleton = $singleton;
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

            $dependencies = $this->reflector->resolveDependencies();
            //recursive build dependencies
            $params = (array_map(function ($dependency) use ($childInjector) {
                return $childInjector->get($dependency['token']);
            }, $dependencies));

            $className = $this->reflector->getClassName();
            return new $className(...$params);
        }
    }
}
