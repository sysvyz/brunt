<?php
/**
 * Created by PhpStorm.
 * User: mb
 * Date: 19.05.16
 * Time: 21:47
 */

namespace Brunt\Reflection\CR;

use Reflection;

class CRField
{

    /**
     * @var \ReflectionProperty
     */
    private $property;


    /**
     * RMethod constructor.
     * @param \ReflectionProperty $property
     */
    public function __construct(\ReflectionProperty $property)
    {
        $this->property = $property;
    }

    /**
     * @return string
     */
    public function getPropertyName():string
    {
        return $this->property->getName();
    }



    /**
     * @return boolean
     */
    public function isPrivate():boolean
    {
        return $this->property->isPrivate();
    }

    /**
     * @return boolean
     */
    public function isPublic():boolean
    {
        return $this->property->isPublic();
    }

    /**
     * @return boolean
     */
    public function isProtected():boolean
    {
        return $this->property->isProtected();
    }

    /**
     * @return boolean
     */
    public function isStatic():boolean
    {
        return $this->property->isStatic();
    }

    /**
     * @return string[]
     */
    public function getModifieres():array
    {
        return Reflection::getModifierNames($this->property->getModifiers());
    }


}
