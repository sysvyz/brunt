<?php


namespace Brunt\Reflection\CR\Cache;


use Brunt\Reflection\CR\CRParam;
use ReflectionParameter;

class CacheCRParam implements CRParam
{

    private $data;

    /**
     * CRParam constructor.
     * @param string $name
     * @param ReflectionParameter $parameter
     */
    public function __construct($data )
    {
        $this->data = $data;

    }

    /**
     * @return boolean
     */
    public function hasType()
    {
        return $this->data['hasType'] ;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->data['getType'];
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->data['getName'];
    }

    public function isBuiltin()
    {
        return $this->data['isBuiltin'];
    }

    /**
     * @return ReflectionParameter
     */
    private function getParameter()
    {

        return $this->data['getParameter'];
    }

    /**
     * @return bool
     */
    public function isPassedByReference()
    {
        return $this->data['isPassedByReference'];
    }

    /**
     * @return bool
     */
    public function isOptional()
    {
        return $this->data['isOptional'];
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