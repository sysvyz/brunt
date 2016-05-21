<?php


namespace BruntTest\Testobjects;


class NonInjectable
{
    public $val;

    /**
     * NonInjectable constructor.
     * @param $val
     */
    public function __construct($val)
    {
        $this->val = $val;
    }
}