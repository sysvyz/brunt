<?php


namespace BruntTest\Testobjects;


class HeavyEngine extends Engine
{
    public function __construct(){
        parent::__construct();
        $this->type = "HeavyEngine";
    }
}