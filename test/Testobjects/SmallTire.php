<?php


namespace BruntTest\Testobjects;

class SmallTire extends Tire
{
    public function __construct()
    {
        parent::__construct();
        $this->type = 'SmallTire';
    }

}