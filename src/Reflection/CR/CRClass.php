<?php
/**
 * Created by PhpStorm.
 * User: mb
 * Date: 19.05.16
 * Time: 21:47
 */

namespace Brunt\Reflection\CR;



use Reflection;

class CRClass
{
    /**
     * @var \ReflectionClass
     */
    private $class;
    /**
     * @var CRMethod[]
     */
    private $methods;

    /**
     * RClass constructor.
     * @param $className
     * @param $methods CRMethod[]
     */
    public function __construct(\ReflectionClass $class, array $methods)
    {
        $this->class = $class;
        $this->methods = $methods;
    }

    /**
     * @return string
     */
    public function getClassName():string
    {
        return $this->class->getName();
    }

    /**
     * @return CRMethod[]
     */
    public function getMethods():array
    {
        return $this->methods;
    }

    /**
     * @return array
     */
    public function getModifiers()
    {
        return Reflection::getModifierNames($this->class->getModifiers());
    }

    /**
     * @return \ReflectionClass
     */
    public function getReflectionClass()
    {
        return $this->class;
    }

}
