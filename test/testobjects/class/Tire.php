<?php
/**
 * Created by PhpStorm.
 * User: mb
 * Date: 10.04.16
 * Time: 16:24
 */
namespace SVZ;


/**
 * @property string type
 * @property number size
 */
class Tire extends Injectable
{
    private static $counter = 0;
    public $type;

    public function __construct()
    {
        $this->type = 'Tire';
    }

}

class FastTire extends Tire
{
    public function __construct()
    {
        parent::__construct();
        $this->type = 'FastTire';
    }

}

class HeavyTire extends Tire
{

    public function __construct()
    {
        parent::__construct();
        $this->type = 'HeavyTire';
    }
}

class SmallTire extends Tire
{
    public function __construct()
    {
        parent::__construct();
        $this->type = 'SmallTire';
    }

}
