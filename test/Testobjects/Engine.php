<?php
/**
 * Created by PhpStorm.
 * User: mb
 * Date: 10.04.16
 * Time: 16:23
 */
namespace PrescriptionTest\Testobjects;


use Prescription\Injectable;

class Engine extends Injectable
{

    static $counterEngine = 0;
    public $type;
    public $counter;

    public function __construct()
    {
        $this->type = "Engine";
        $this->counter = ++self::$counterEngine;
    }

}

