<?php
/**
 * Created by PhpStorm.
 * User: mb
 * Date: 18.04.16
 * Time: 14:00
 */

namespace PrescriptionTest\Testobjects;


use Prescription\Injector;
use Prescription\Provider\ClassProvider;

class FastCar extends Car
{
    public function __construct(Engine $engine, Injector $injector)
    {
        $this->engine = $engine;
        $this->tire0 = $injector->get(SmallTire::class);
        $this->tire1 = $injector->get(SmallTire::class);
        $this->tire2 = $injector->get(HeavyTire::class);
        $this->tire3 = $injector->get(HeavyTire::class);
        $this->name = 'FASTCAR';
    }

    public static function _DI_PROVIDERS()
    {
        return [
            SmallTire::class => ClassProvider::init(SmallTire::class, false),
            HeavyTire::class=> ClassProvider::init(HeavyTire::class, false),

        ]+parent::_DI_PROVIDERS();
    }

}