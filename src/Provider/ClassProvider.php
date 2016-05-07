<?php
/**
 * Created by PhpStorm.
 * User: mb
 * Date: 17.04.16
 * Time: 01:28
 */

namespace Prescription\Provider;

use Prescription\Exception\CircularDependencyException;
use Prescription\Injector;
use Prescription\Provider\Reflector as RF;


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

    private static function validate(RF $reflector, $path = [])
    {
        //get class name
        $className = $reflector->getClassName();
        if ($className == Injector::class){
            return;
        }
        if (in_array($className, $path)){
            throw new CircularDependencyException ($className . ' must not depend on it self');
        }
        array_push($path, $reflector->getClassName());
        foreach ($reflector->getConstructorParams() as $dependency) {
            if($dependency->getType() && !$dependency->getType()->isBuiltin()){
                $nextReflector = new RF($dependency->getType().'');
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

        return $this->instance = new $className(...$params);
    }
}
