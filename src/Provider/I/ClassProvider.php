<?php


namespace Brunt\Provider\I;


use Brunt\Provider\Provider;
use Brunt\Reflection\Reflector;


interface ClassProvider extends Provider
{
    /**
     * @return string
     */
    public function getClass();

    /**
     * @return Reflector
     */
    public function getReflector();

}