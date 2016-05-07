<?php
/**
 * Created by PhpStorm.
 * User: mb
 * Date: 18.04.16
 * Time: 14:04
 */

namespace BruntTest\Testobjects;


use Brunt\Injectable;

class CircB extends Injectable
{


    /**
     * CircB constructor.
     * @param CircC $b
     */
    public function __construct(CircC $b)
    {

    }
}
