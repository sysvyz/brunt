<?php


namespace BruntTest\Testobjects;


use Brunt\Injector;
use Brunt\Provider\Classes\ClassProvider;

/**
 * Class FastCar
 * @package BruntTest\Testobjects
 */
class FastCar extends Car
{
    public function __construct(Engine $engine, Injector $injector)
    {
        $this->engine = $engine;
        $this->tire0 = $injector->get('%FRONT_TIRE%');
        $this->tire1 = $injector->get('%FRONT_TIRE%');
        $this->tire2 = $injector->get('%BACK_TIRE%');
        $this->tire3 = $injector->get('%BACK_TIRE%');
        $this->name = 'FASTCAR';
    }

    public static function _DI_PROVIDERS()
    {
        return [
            '%FRONT_TIRE%' => ClassProvider::init(SmallTire::class, false),
            '%BACK_TIRE%'=> ClassProvider::init(HeavyTire::class, false),

        ]+parent::_DI_PROVIDERS();
    }

}