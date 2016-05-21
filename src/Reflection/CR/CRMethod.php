<?php
/**
 * Created by PhpStorm.
 * User: mb
 * Date: 19.05.16
 * Time: 21:47
 */

namespace Brunt\Reflection\CR;

use Reflection;

class CRMethod
{
    /**
     * @var CRParam[]
     */
    public $params;
    /**
     * @var \ReflectionMethod
     */
    private $method;


    /**
     * RMethod constructor.
     * @param \ReflectionMethod $method
     * @param array $params
     */
    public function __construct(\ReflectionMethod $method, array $params)
    {
        $this->params = $params;
        $this->method = $method;
    }

    /**
     * @return string
     */
    public function getMethodName()
    {
        return $this->method->getName();
    }

    /**
     * @return CRParam[]
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * @return boolean
     */
    public function isPrivate()
    {
        return $this->method->isPrivate();
    }

    /**
     * @return boolean
     */
    public function isPublic()
    {
        return $this->method->isPublic();
    }

    /**
     * @return boolean
     */
    public function isProtected()
    {
        return $this->method->isProtected();
    }

    /**
     * @return boolean
     */
    public function isStatic()
    {
        return $this->method->isStatic();
    }

    /**
     * @return array
     */
    public function getModifieres()
    {
        return Reflection::getModifierNames($this->method->getModifiers());
    }


}