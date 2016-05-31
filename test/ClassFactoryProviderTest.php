<?php
/**
 * Created by PhpStorm.
 * User: mb
 * Date: 21.05.16
 * Time: 04:08
 */

namespace BruntTest;


use Brunt\Injector;
use Brunt\Provider\Classes\ClassFactoryProvider;
use Brunt\Provider\Classes\ClassProvider;
use Brunt\Provider\Lazy\T\ProxyTrait;
use BruntTest\Testobjects\HeavyTire;
use BruntTest\Testobjects\Tire;

class ClassFactoryProviderTest extends \PHPUnit_Framework_TestCase
{


    public function testProviders()
    {
        // Arrange
        $injector = new Injector(null);
        $injector->providers([
            Tire::class => ClassFactoryProvider::init(Tire::class,function (Injector $injector) {
                return new HeavyTire();
            })
        ]);
        /** @var Tire $entity */
        $entity = $injector->{Tire::class};
        $this->assertInstanceOf(HeavyTire::class,$entity);
        $this->assertEquals($entity->type,'HeavyTire');
        $this->assertNotSame($entity,$injector->{Tire::class});
    }
    public function testProvidersLazy()
    {
        // Arrange
        $injector = new Injector(null);
        $injector->providers([
            Tire::class => ClassFactoryProvider::init(Tire::class,function (Injector $injector) {//no HeavyTire
                return new HeavyTire();
            })->lazy()
        ]);
        /** @var Tire $entity */
        $entity = $injector->{Tire::class};
        $this->assertInstanceOf(Tire::class,$entity);
        $this->assertNotInstanceOf(HeavyTire::class,$entity);  //not
        $this->assertEquals($entity->type,'HeavyTire');
        $this->assertNotSame($entity,$injector->{Tire::class});
    }

    public function testProvidersLazyConcreteProxy()
    {
        // Arrange
        $injector = new Injector(null);
        $injector->providers([
            Tire::class => ClassFactoryProvider::init(HeavyTire::class,function (Injector $injector) {//as HeavyTire
                return new HeavyTire();
            })->lazy()
        ]);
        /** @var Tire $entity */
        $entity = $injector->{Tire::class};
        $this->assertInstanceOf(Tire::class,$entity);
        $this->assertInstanceOf(HeavyTire::class,$entity);//is HeavyTire
        $this->assertEquals($entity->type,'HeavyTire');
        $this->assertNotSame($entity,$injector->{Tire::class});
    }

    public function testProvidersSingleton()
    {
        // Arrange
        $injector = new Injector(null);
        $injector->providers([
            Tire::class => ClassFactoryProvider::init(Tire::class,function (Injector $injector) {
                return new HeavyTire();
            })->singleton()
        ]);
        /** @var Tire $entity */
        $entity = $injector->{Tire::class};
        $this->assertInstanceOf(HeavyTire::class,$entity);
        $this->assertEquals($entity->type,'HeavyTire');
        $this->assertSame($entity,$injector->{Tire::class});
    }


    public function testProvidersLazySingleton()
    {
        // Arrange
        $injector = new Injector(null);

        $p = ClassFactoryProvider::init(HeavyTire::class,function (Injector $injector) {//no HeavyTire
            return new HeavyTire();
        })->singleton();
     
        $injector->providers([
            Tire::class => $p->lazy()
        ]);
        /** @var Tire $entity */
        $entity = $injector->{Tire::class};
        $this->assertInstanceOf(Tire::class,$entity);
        $this->assertInstanceOf(HeavyTire::class,$entity);  //not
        $this->assertEquals($entity->type,'HeavyTire');
        $entity2 = $injector->{Tire::class};
        $this->assertEquals($entity2->type,'HeavyTire');

        $this->assertSame($entity->getInstance(),$entity2->getInstance());
    }

    public function testProvidersLazySingleton2()
    {
        // Arrange
        $injector = new Injector(null);
        $injector->providers([
            Tire::class => ClassProvider::init(HeavyTire::class)->singleton()->lazy()
        ]);
        /** @var Tire|ProxyTrait $entity */
        $entity = $injector->{Tire::class};
        $this->assertInstanceOf(Tire::class,$entity);
        $this->assertInstanceOf(HeavyTire::class,$entity);  //not
        $this->assertEquals($entity->type,'HeavyTire');
        $this->assertSame($entity->getInstance(),$injector->{Tire::class}->getInstance());
        $entity->a = "a";
        $this->assertEquals("a",$injector->{Tire::class}->a);
    }

}