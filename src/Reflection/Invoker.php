<?php
/**
 * Created by PhpStorm.
 * User: mb
 * Date: 29.05.16
 * Time: 18:45
 */

namespace Brunt\Reflection;


use Brunt\Injector;

class Invoker
{
    /**
     * @var Injector
     */
    private $injector;


    /**
     * Invoker constructor.
     * @param Injector $injector
     */
    public function __construct(Injector $injector)
    {
        $this->injector = $injector;
    }

    public function invoke($object, $function)
    {

        $reflectionClass = new \ReflectionClass($object);
        $reflector = new Reflector($reflectionClass);

        $reflectionMethod = new \ReflectionMethod($object, $function);
        $dependencies = $reflector->resolveDependencies($reflectionMethod->getParameters(),$function);
        
        $params = (array_map(function ($dependency) {
            return $this->injector->get($dependency['token']);
        }, $dependencies));
        return call_user_func_array([$object, $function], $params);
    }


}