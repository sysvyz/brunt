<?php


namespace Brunt\Reflection\CR;


use ReflectionParameter;

interface CRParam
{


    /**
     * @return boolean
     */
    public function hasType();

    /**
     * @return string
     */
    public function getType();

    /**
     * @return string
     */
    public function getName();

    public function isBuiltin();

    /**
     * @return bool
     */
    public function isPassedByReference();

    /**
     * @return bool
     */
    public function isOptional();
    public function toArray();

}