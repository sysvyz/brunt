<?php
/**
 * Created by PhpStorm.
 * User: mb
 * Date: 18.04.16
 * Time: 15:29
 */

namespace PrescriptionTest\Testobjects;


use Prescription\Injectable;

class NonInjectableWrapper extends Injectable
{
    /**
     * @var NonInjectable
     */
    public $nonInjectable;


    /**
     * NonInjectableWrapper constructor.
     * @param NonInjectable $nonInjectable
     */
    public function __construct(NonInjectable $nonInjectable)
    {
        $this->nonInjectable = $nonInjectable;
    }
}