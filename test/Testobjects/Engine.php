<?php


namespace BruntTest\Testobjects;


use Brunt\Injectable;

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

