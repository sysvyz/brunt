<?php
/**
 * Created by PhpStorm.
 * User: mb
 * Date: 17.04.16
 * Time: 01:28
 */

namespace Prescription\Provider\Rmk;

use Prescription\Exception\CircularDependencyException;
use Prescription\InjectableInterface;
use Prescription\Injector;
use Prescription\Provider\Provider;

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

    function __invoke(Injector $injector)
    {
        //return singleton if instance is set and singleton mode
        if ($this->singleton && $this->instance !== null) {
            return $this->instance;
        }

        $providers = $this->reflector->getProviders();
        $dependencies = $this->reflector->resolveDependencies();

        $childInjector = $injector->getChild($providers);

        //recursive build dependencies
        $params = (array_map(function($dependency) use($childInjector){
            return $childInjector->get($dependency['token']);
        },$dependencies));


        $className = $this->reflector->getClassName();
        return new $className(...$params);
    }

}
