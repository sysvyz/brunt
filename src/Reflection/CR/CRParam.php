<?php


namespace Brunt\Reflection\CR;


use ReflectionParameter;

class CRParam
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
     * @return \ReflectionType
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return ReflectionParameter
     */
    public function getParameter()
    {
        return $this->parameter;
    }


}