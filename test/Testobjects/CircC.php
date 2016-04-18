<?php
/**
 * Created by PhpStorm.
 * User: mb
 * Date: 18.04.16
 * Time: 14:05
 */

namespace PrescriptionTest\Testobjects;


use Prescription\Injectable;

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