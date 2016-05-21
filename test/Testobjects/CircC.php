<?php


namespace BruntTest\Testobjects;


use Brunt\Injectable;

class CircC extends Injectable
{

    /**
     * CircC constructor.
     * @param CircB $b
     */
    public function __construct(CircB $b)
    {

    }
}