<?php
/**
 * Created by PhpStorm.
 * User: mb
 * Date: 10.04.16
 * Time: 16:22
 */
namespace SVZ;


/**
 * @property Engine engine*  Some comment
 * @property TireSet tireSet*
 * @property Logger logger*
 * @property string type
 */
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
            '%CAR_NAME%'=> function(){
                return 'CAR';
            },
        ];
    }
}


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

class SmallCar extends Car
{
    public static function _DI_PROVIDERS()
    {
        return [
            '%CAR_NAME%'=> function(){
                return 'SMALLCAR';
            },
        ]+parent::_DI_PROVIDERS();
    }


}