<?php


namespace Brunt\Reflection\CR\Reflective;


use Brunt\Reflection\CR\CRParam;
use ReflectionParameter;

class ReflectiveCRParam implements CRParam
{
    /**
     * @var string
     */
    private $name;
    /**
     * @var \ReflectionType
     */
    private $type;
    /**
     * @var ReflectionParameter
     */
    private $parameter;

    /**
     * CRParam constructor.
     * @param string $name
     * @param ReflectionParameter $parameter
     */
    public function __construct(string $name, ReflectionParameter $parameter = null)
    {
        $this->name = $name;
        $this->type = $parameter->getType();
        $this->parameter = $parameter;
    }

    /**
     * @return boolean
     */
    public function hasType()
    {
        return $this->type && true ;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type.'';
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    public function isBuiltin()
    {
        return $this->hasType() && $this->type->isBuiltin();
    }

    /**
     * @return ReflectionParameter
     */
    private function getParameter()
    {

        return $this->parameter;
    }

    /**
     * @return bool
     */
    public function isPassedByReference()
    {
        return $this->getParameter()->isPassedByReference();
    }

    /**
     * @return bool
     */
    public function isOptional()
    {
        return $this->getParameter()->isOptional();
    }

    public function toArray(){
        return [
            'isBuiltin' =>$this->isBuiltin(),
            'isOptional' =>$this->isOptional(),
            'isPassedByReference' =>$this->isPassedByReference(),
            'getName' =>$this->getName(),
            'getType' =>$this->getType(),
            'hasType' =>$this->hasType(),
            ];


    }

}