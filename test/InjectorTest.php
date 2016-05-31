<?php
namespace BruntTest;

use Brunt\Exception\ProviderNotFoundException;
use Brunt\Injector;
use Brunt\Provider;
use Brunt\Provider\Classes\ClassProvider;
use Brunt\Provider\Singleton\SingletonProvider;
use BruntTest\Testobjects\Car;
use BruntTest\Testobjects\Engine;
use BruntTest\Testobjects\FastCar;
use BruntTest\Testobjects\HeavyEngine;
use BruntTest\Testobjects\HeavyTire;
use BruntTest\Testobjects\SmallTire;
use PHPUnit_Framework_TestCase;
use function Brunt\bind;

class InjectorTest extends PHPUnit_Framework_TestCase
{
    public function testInjector()
    {
        // Arrange
        $injector = new Injector(null);
        $b = $injector->get(Injector::class);
        $this->assertSame($injector, $b);

    }

    public function testChildInjectorIsNewInstance()
    {
        // Arrange
        $injector = new Injector(null);
        $b = $injector->getChild();
        $this->assertNotSame($injector, $b);

    }


    public function testProvider()
    {
        $injector = new Injector(null);

        /** @var ClassProvider $provider */
        $provider = ClassProvider::init(Engine::class);
        $engine = $provider($injector);
        $this->assertInstanceOf(Engine::class, $engine);

    }

    public function testNoProvider()
    {
        $injector = new Injector(null);;

        /** @var Engine $engine */
        $engine = $injector->get(Engine::class);

        $this->assertInstanceOf(Engine::class,$engine);
        $this->assertTrue(true);

    }

    public function testGet()
    {
        // Arrange
        $injector = new Injector(null);
        $injector->providers([Engine::class => ClassProvider::init(Engine::class)]);

        /** @var Engine $engine */
        $engine = $injector->get(Engine::class);
        $this->assertInstanceOf(Engine::class, $engine);
        $this->assertNotInstanceOf(HeavyEngine::class, $engine);

    }

    public function testGetLazy()
    {
        // Arrange
        $injector = new Injector(null);
        $injector->providers([Engine::class => ClassProvider::init(Engine::class)->lazy()]);

        /** @var Engine $engine */
        $engine = $injector->get(Engine::class);
        $this->assertInstanceOf(Engine::class, $engine);
        $this->assertNotInstanceOf(HeavyEngine::class, $engine);

    }

    public function testPolymorphism()
    {
        // Arrange
        $injector = new Injector(null);
        $injector->providers([Engine::class => ClassProvider::init(HeavyEngine::class)]);
        try {
            /** @var Engine $engine */
            $engine = $injector->get(Engine::class);
            $this->assertInstanceOf(Engine::class, $engine);
            $this->assertInstanceOf(HeavyEngine::class, $engine);
        } catch (ProviderNotFoundException $e) {
            $this->assertTrue(false);
        }
    }

    public function testLazyPolymorphism()
    {
        // Arrange
        $injector = new Injector(null);
        $injector->providers([Engine::class => ClassProvider::init(HeavyEngine::class)->lazy()]);
        try {
            /** @var Engine $engine */
            $engine = $injector->get(Engine::class);
            $this->assertInstanceOf(Engine::class, $engine);
            $this->assertInstanceOf(HeavyEngine::class, $engine);
        } catch (ProviderNotFoundException $e) {
            $this->assertTrue(false);
        }
    }

    public function testMagicGet()
    {
        // Arrange
        $injector = new Injector(null);
        $injector->providers([Engine::class => ClassProvider::init(Engine::class)]);
        try {
            /** @var Engine $engine */
            $engine = $injector->{Engine::class};
            $this->assertInstanceOf(Engine::class, $engine);


        } catch (ProviderNotFoundException $e) {
            $this->assertTrue(false);
        }
    }

    public function testSingletonProvider()
    {
        $injector = new Injector(null);

        /** @var ClassProvider $provider */
        $provider = ClassProvider::init(Engine::class);
        $singletonProvider = new SingletonProvider($provider);

        $this->assertSame($singletonProvider($injector), $singletonProvider($injector));

    }

    public function testSingleton()
    {
        // Arrange
        $injector = new Injector(null);
        $injector->providers([Engine::class => ClassProvider::init(Engine::class)->singleton()]);
        try {
            /** @var Engine $engine */
            $engine = $injector->get(Engine::class);
            $engine2 = $injector->get(Engine::class);
            $this->assertSame($engine, $engine2);

        } catch (ProviderNotFoundException $e) {
            $this->assertTrue(false);
        }
    }

    public function testMultiple()
    {
        // Arrange
        $injector = new Injector(null);
        $injector->providers([Engine::class => ClassProvider::init(Engine::class)]);

        /** @var Engine $engine */
        $engine = $injector->get(Engine::class);

        /** @var Engine $engine2 */
        $engine2 = $injector->get(Engine::class);
        $this->assertNotSame($engine, $engine2);


    }


    public function testDependecies()
    {
        // Arrange
        $injector = new Injector(null);

        $injector->{Car::class}(ClassProvider::init(FastCar::class));
        $injector->{Engine::class}(ClassProvider::init(Engine::class)->singleton());


        /** @var Car $car */
        $car = $injector->get(Car::class);
        /** @var Engine $engine */
        $engine = $injector->get(Engine::class);

        $this->assertSame($car->engine, $engine);
        $this->assertNotSame($car->tire0, $car->tire1);
        $this->assertNotSame($car->tire0, $car->tire2);
        $this->assertNotSame($car->tire0, $car->tire3);
        $this->assertNotSame($car->tire1, $car->tire2);
        $this->assertNotSame($car->tire1, $car->tire3);
        $this->assertNotSame($car->tire2, $car->tire3);


        $this->assertInstanceOf(SmallTire::class, $car->tire0);
        $this->assertInstanceOf(SmallTire::class, $car->tire1);
        $this->assertInstanceOf(HeavyTire::class, $car->tire2);
        $this->assertInstanceOf(HeavyTire::class, $car->tire3);
    }


    public function testBindings()
    {
        // Arrange
        $injector = new Injector(null);
        $injector->bind(
            bind(Car::class)->toClass(FastCar::class),
            bind(Engine::class)->toClass(Engine::class)->singleton()
        );

        /** @var Car $car */
        $car = $injector->get(Car::class);
        /** @var Engine $engine */
        $engine = $injector->get(Engine::class);

        $this->assertSame($car->engine, $engine);
        $this->assertNotSame($car->tire0, $car->tire1);
        $this->assertNotSame($car->tire0, $car->tire2);
        $this->assertNotSame($car->tire0, $car->tire3);
        $this->assertNotSame($car->tire1, $car->tire2);
        $this->assertNotSame($car->tire1, $car->tire3);
        $this->assertNotSame($car->tire2, $car->tire3);

        $this->assertInstanceOf(SmallTire::class, $car->tire0);
        $this->assertInstanceOf(SmallTire::class, $car->tire1);
        $this->assertInstanceOf(HeavyTire::class, $car->tire2);
        $this->assertInstanceOf(HeavyTire::class, $car->tire3);

    }


}