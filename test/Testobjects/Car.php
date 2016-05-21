<?php

namespace BruntTest\Testobjects;

use Brunt\InjectableInterface;
use Brunt\Injector;
use Brunt\Provider\ClassProvider;
use Brunt\Provider\FactoryProvider;
use Brunt\Provider\ValueFactoryProvider;


class Car implements InjectableInterface
{


    public $engine;
    public $tire0;
    public $tire1;
    public $tire2;
    public $tire3;
    public $name;

    /**
     * Car constructor.
     * @param Engine $engine
     * @param Injector $injector
     * @param string $name
     * @internal param Tire $tire
     */
    public function __construct(Engine $engine, Injector $injector,string $name)
    {
        $this->engine = $engine;
        $this->tire0 = $injector->get(Tire::class);
        $this->tire1 = $injector->get(Tire::class);
        $this->tire2 = $injector->get(Tire::class);
        $this->tire3 = $injector->get(Tire::class);
        $this->name = $name;
    }

    public static function _DI_DEPENDENCIES()
    {
        return
            [
                'name' => '%CAR_NAME%'
            ];
    }


    public static function _DI_PROVIDERS()
    {
        return [
            Tire::class => ClassProvider::init(SmallTire::class, false),
            '%CAR_NAME%'=> ValueFactoryProvider::init(function (){
                return 'CAR';
            }),
        ];
    }
}

