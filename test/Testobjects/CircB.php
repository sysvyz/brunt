<?php
/**
 * Created by PhpStorm.
 * User: mb
 * Date: 18.04.16
 * Time: 14:04
 */

namespace PrescriptionTest\Testobjects;


use Prescription\Injectable;

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
