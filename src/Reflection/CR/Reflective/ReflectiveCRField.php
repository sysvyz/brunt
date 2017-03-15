<?php


namespace Brunt\Reflection\CR\Reflective;

use Brunt\Reflection\CR\CRField;
use Reflection;

class ReflectiveCRField implements CRField
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
    public function getName():string
    {
        return $this->property->getName();
    }


    /**
     * @return boolean
     */
    public function isPrivate()
    {
        return $this->property->isPrivate();
    }

    /**
     * @return boolean
     */
    public function isPublic()
    {
        return $this->property->isPublic();
    }

    /**
     * @return boolean
     */
    public function isProtected()
    {
        return $this->property->isProtected();
    }

    /**
     * @return boolean
     */
    public function isStatic()
    {
        return $this->property->isStatic();
    }

    /**
     * @return string[]
     */
    public function getModifiers():array
    {
        return Reflection::getModifierNames($this->property->getModifiers());
    }

    public function toArray()
    {
    return [
        'getModifiers' => $this->getModifiers(),
        'isStatic'=>$this->isStatic(),
        'isProtected'=>$this->isProtected(),
        'isPublic'=>$this->isPublic(),
        'isPrivate'=>$this->isPrivate(),
        'getName'=>$this->getName()
    ];
    }

}
