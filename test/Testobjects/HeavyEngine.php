<?php
/**
 * Created by PhpStorm.
 * User: mb
 * Date: 18.04.16
 * Time: 14:03
 */

namespace BruntTest\Testobjects;


class HeavyEngine extends Engine
{
    public function __construct(){
        parent::__construct();
        $this->type = "HeavyEngine";
    }
}