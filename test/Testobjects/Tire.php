<?php


namespace BruntTest\Testobjects;
use Brunt\Injectable;


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
