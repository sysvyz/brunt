<?php


namespace Brunt\Reflection\CR\Reflective;

use Brunt\Reflection\CR\CRMethod;
use Brunt\Reflection\CR\CRParam;
use Reflection;

class ReflectiveCRMethod  implements CRMethod
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
    public function getName()
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
    public function getModifiers()
    {
        return Reflection::getModifierNames($this->method->getModifiers());
    }

    public function toArray()
    {
        return [
            'getModifiers' => $this->getModifiers(),
            'getParams' =>  array_map( function (CRParam $param) {
                return $param->toArray();
            },$this->getParams()),
            'isStatic' =>$this->isStatic(),
            'isProtected' =>$this->isProtected(),
            'isPublic' =>$this->isPublic(),
            'isPrivate' =>$this->isPrivate(),
            'getName' =>$this->getName()
        ];
    }
}