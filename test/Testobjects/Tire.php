<?php
/**
 * Created by PhpStorm.
 * User: mb
 * Date: 10.04.16
 * Time: 16:24
 */
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
