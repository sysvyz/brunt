<?php
/**
 * Created by PhpStorm.
 * User: mb
 * Date: 18.04.16
 * Time: 14:04
 */

namespace BruntTest\Testobjects;


class SmallEngine extends Engine
{
    public function __construct(){
        parent::__construct();
        $this->type = "SmallEngine";
    }
}
