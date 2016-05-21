<?php


namespace BruntTest\Testobjects;


class SmallEngine extends Engine
{
    public function __construct(){
        parent::__construct();
        $this->type = "SmallEngine";
    }
}
