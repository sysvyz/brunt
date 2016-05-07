<?php
/**
 * Created by PhpStorm.
 * User: mb
 * Date: 17.04.16
 * Time: 01:28
 */

namespace Brunt\Provider;

use Brunt\Exception\CircularDependencyException;
use Brunt\Injector;
use Brunt\Provider\Reflector as RF;


class ClassProvider implements Provider
{
    /**
     * @var Reflector
     */
    private $reflector;
    /**
     * @var bool
     */
    private $singleton;

    /**
     * @var mixed
     */
    private $instance = null;
    /**
     * @var string
     */
    private $class;


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
     * if A depends on B and B on A then this cant be resolved.
     * dependencies MUST be A DAG https://en.wikipedia.org/wiki/Directed_acyclic_graph
     *
     *
     * @param Reflector $reflector
     * @param array $path
     */
    private static function validate(RF $reflector, $path = [])
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
                $nextReflector = new RF($dependency->getType() . '');
                self::validate($nextReflector, $path);
            }
        }
    }

    /**
     * @param $class
     * @param bool $singleton
     * @return ClassProvider
     */
    public static function init($class, $singleton = true)
    {
        return new static($class, $singleton);
    }


    /**
     * Get the current Injector,.
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
        //return singleton if instance is set and singleton mode
        if ($this->singleton && $this->instance !== null) {
            return $this->instance;
        }

        $providers = $this->reflector->getProviders();
        $childInjector = $injector->getChild($providers);

        $dependencies = $this->reflector->resolveDependencies();
        //recursive build dependencies
        $params = (array_map(function ($dependency) use ($childInjector) {
            return $childInjector->get($dependency['token']);
        }, $dependencies));

        $className = $this->reflector->getClassName();
        return $this->instance = new $className(...$params);
    }
}
