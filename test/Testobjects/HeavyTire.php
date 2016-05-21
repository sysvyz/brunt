<?php


namespace BruntTest\Testobjects;


class HeavyTire extends Tire
{

    public function __construct()
    {
        parent::__construct();
        $this->type = 'HeavyTire';
    }
}

