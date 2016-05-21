<?php


namespace BruntTest\Testobjects;


class NonInjectableC
{

    /**
     * @var NonInjectableB
     */
    public $b;

    /**
     * NonInjectable constructor.
     * @param NonInjectableB $b
     */
    public function __construct(NonInjectableB $b)
    {

        $this->b = $b;
    }
}