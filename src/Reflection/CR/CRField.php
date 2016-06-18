<?php


namespace Brunt\Reflection\CR;

interface CRField
{

    public function getName():string;

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
     * @return string[]
     */
    public function getModifieres():array;

    public function toArray();

}
