<?php
/**
 * Created by PhpStorm.
 * User: mb
 * Date: 18.04.16
 * Time: 15:29
 */

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