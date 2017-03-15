<?php


namespace Brunt\Provider\Contract;


use Brunt\Reflection\Reflector;


interface ClassProviderInterface extends ProviderInterface
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