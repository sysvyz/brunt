<?php


namespace Brunt\Reflection\CR;


use Reflection;

interface CRClass
{




    public function toArray();

    /**
     * @return string
     */
    public function getClassName();

    /**
     * @return array
     */
    public function getModifiers();

    /**
     * @return CRMethod[]
     */
    public function getMethods():array;

    /**
     * @return CRField[]
     */
    public function getFields();
    /**
     * @return string
     */
    public function getFileName();
    public function hasDependencies();
    public function hasProviders();

    /**
     * @return CRMethod
     */
    public function getConstructor();
}
