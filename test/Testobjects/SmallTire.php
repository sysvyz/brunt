<?php
/**
 * Created by PhpStorm.
 * User: mb
 * Date: 18.04.16
 * Time: 14:02
 */

namespace BruntTest\Testobjects;

class SmallTire extends Tire
{
    public function __construct()
    {
        parent::__construct();
        $this->type = 'SmallTire';
    }

}