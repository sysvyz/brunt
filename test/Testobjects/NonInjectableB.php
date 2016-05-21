<?php


namespace BruntTest\Testobjects;


class NonInjectableB
{
    public $val;

    /**
     * NonInjectable constructor.
     * @param $val
     */
    public function __construct()
    {

    }
}