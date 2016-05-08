<?php
use Brunt\Exception\CircularDependencyException;
use Brunt\Exception\ProviderNotFoundException;
use Brunt\Injector;
use Brunt\Provider;
use Brunt\Provider\ClassProvider;
use Brunt\Provider\FactoryProvider;
use BruntTest\Testobjects\Car;
use BruntTest\Testobjects\CircA;
use BruntTest\Testobjects\CircB;
use BruntTest\Testobjects\CircC;
use BruntTest\Testobjects\Controller;
use BruntTest\Testobjects\ControllerA;
use BruntTest\Testobjects\Engine;
use BruntTest\Testobjects\FastCar;
use BruntTest\Testobjects\HeavyEngine;
use BruntTest\Testobjects\HeavyTire;
use BruntTest\Testobjects\NonInjectable;
use BruntTest\Testobjects\NonInjectableB;
use BruntTest\Testobjects\NonInjectableWrapper;
use BruntTest\Testobjects\SmallTire;
use function Brunt\bind;

/**
 * Created by PhpStorm.
 * User: mb
 * Date: 17.04.16
 * Time: 15:35
 */
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
        try {
            /** @var Engine $engine */
            $engine = $injector->get(Engine::class);
            $this->assertTrue(false);
        } catch (ProviderNotFoundException $e) {
            $this->assertTrue(true);
        }
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
    public function testSingleton()
    {
        $injector = new Injector(null);

        /** @var ClassProvider $provider */
        $provider = ClassProvider::init(Engine::class);
        $singletonProvider = new Provider\SingletonProvider($provider);

        $this->assertSame($singletonProvider($injector), $singletonProvider($injector));

    }

    public function testSingletonOld()
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
        $injector->providers([Engine::class => ClassProvider::init(Engine::class, false)]);

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
        $injector->providers([
            Car::class => ClassProvider::init(FastCar::class),
            Engine::class => ClassProvider::init(Engine::class)->singleton()
        ]);

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
        $injector->bind([
            bind(Car::class)->toClass(FastCar::class),
            bind(Engine::class)->toClass(Engine::class)->asSingleton(),
        ]);

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


    public function testProviders()
    {
        // Arrange
        $injector = new Injector(null);
        $injector->providers([
            Controller::class => ClassProvider::init(ControllerA::class),
            '%BASE_URL%' => function () {
                return 'http://sysvyz.org/';
            }
        ]);


        /** @var ControllerA $ctrl */
        $ctrl = $injector->get(Controller::class);

        $ctrl->serviceZ;
        $this->assertSame($ctrl->requestService, $ctrl->serviceZ->requestService);
        $this->assertNotSame($ctrl->requestService, $ctrl->serviceZ->serviceY->requestService);
        $this->assertNotEquals($ctrl->requestService->url, $ctrl->serviceZ->serviceY->requestService->url);

    }


    public function testSelfReference()
    {
        // Arrange
        $injector = new Injector(null);
        try {
            $injector->providers([
                CircA::class => ClassProvider::init(CircA::class),
            ]);


            //this should not execute
            $this->assertTrue(false);
            $injector->get(CircA::class);
        } catch (CircularDependencyException $e) {
            $this->assertTrue(true);
        }
    }

    public function testCircularReference()
    {
        // Arrange
        $injector = new Injector(null);
        try {
            $injector->providers([
                CircB::class => ClassProvider::init(CircB::class),
                CircC::class => ClassProvider::init(CircC::class),
            ]);

            $injector->get(CircC::class);
            //this should not execute
            $this->assertTrue(false);
        } catch (CircularDependencyException $e) {
            $this->assertTrue(true);
        }

    }


    public function testNonInjectable()
    {
        // Arrange
        $injector = new Injector(null);

        $injector->providers([
            NonInjectableB::class => ClassProvider::init(NonInjectableB::class),
        ]);
        /** @var NonInjectableB $entity */
        $entity = $injector->get(NonInjectableB::class);
        $this->assertInstanceOf(NonInjectableB::class, $entity);

    }


    public function testNonInjectableDependency()
    {
        // Arrange
        $injector = new Injector(null);

        $injector->providers(
            [
                NonInjectableWrapper::class => ClassProvider::init(NonInjectableWrapper::class),
                NonInjectable::class => FactoryProvider::init(function () {
                    return new NonInjectable(5);
                }) ,
            ]
        );

        /** @var NonInjectableWrapper $wrapper */
        $wrapper = $injector->get(NonInjectableWrapper::class);
        $this->assertEquals($wrapper->nonInjectable->val, 5);


    }

    public function testNonInjectableDependencyFail()
    {
        // Arrange
        $injector = new Injector(null);

        $injector->providers([
            NonInjectableWrapper::class => ClassProvider::init(NonInjectableWrapper::class),
        ]);

        /** @var NonInjectableWrapper $wrapper */
        try {

            $wrapper = $injector->get(NonInjectableWrapper::class);
            $this->assertTrue(false);
        } catch (ProviderNotFoundException $e) {
            $this->assertTrue(true);
        }
    }


}