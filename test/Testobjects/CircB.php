<?php


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
