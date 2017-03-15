<?php


namespace Brunt\Reflection\CR;

use Reflection;

interface CRMethod
{


    /**
     * @return string
     */
    public function getName();

    /**
     * @return CRParam[]
     */
    public function getParams();
    /**
     * @return boolean
     */
    public function isPrivate();
    /**
     * @return boolean
     */
    public function isPublic();

    /**
     * @return boolean
     */
    public function isProtected();
    /**
     * @return boolean
     */
    public function isStatic();
    /**
     * @return array
     */
    public function getModifiers();

    public function toArray();
}