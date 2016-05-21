<?php


namespace BruntTest\Testobjects;


class FastTire extends Tire
{
    public function __construct()
    {
        parent::__construct();
        $this->type = 'FastTire';
    }

}
