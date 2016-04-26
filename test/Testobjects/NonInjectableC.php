<?php
/**
 * Created by PhpStorm.
 * User: mb
 * Date: 18.04.16
 * Time: 15:29
 */

namespace PrescriptionTest\Testobjects;


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