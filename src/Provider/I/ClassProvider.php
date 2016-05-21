<?php
/**
 * Created by PhpStorm.
 * User: mb
 * Date: 21.05.16
 * Time: 01:51
 */

namespace Brunt\Provider\I;


use Brunt\Provider\Provider;
use Brunt\Reflection\Reflector;


interface ClassProvider extends Provider
{

    public function getClass();

    /**
     * @return Reflector
     */
    public function getReflector();

}