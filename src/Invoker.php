<?php
/**
 * Created by PhpStorm.
 * User: mb
 * Date: 29.05.16
 * Time: 18:45
 */

namespace Brunt;


use Brunt\Injector;
use Brunt\Reflection\CR\CRMethod;
use Brunt\Reflection\CR\CRParam;
use Brunt\Reflection\CR\Reflective\ReflectiveCRMethod;
use Brunt\Reflection\CR\Reflective\ReflectiveCRParam;
use Brunt\Reflection\ReflectorFactory;

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
//TODO opimize

        $reflector = ReflectorFactory::buildReflectorByClassName(get_class($object));

        $reflectionMethod = new \ReflectionMethod($object, $function);

        $params = $reflectionMethod->getParameters();
        $ps = [];
        foreach ($params as $param) {
            $ps  [$param->getName()] = new ReflectiveCRParam($param->getName() . "", $param);
        }

        $crMethod = new ReflectiveCRMethod($reflectionMethod,$ps);
        $dependencies = $reflector->resolveDependencies($crMethod->getParams(),$function);
        
        $params = (array_map(function ($dependency) {
            return $this->injector->get($dependency['token']);
        }, $dependencies));
        return call_user_func_array([$object, $function], $params);
    }


}