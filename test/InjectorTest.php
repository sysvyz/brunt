<?php
use SVZ\Car;
use SVZ\CircA;
use SVZ\CircB;
use SVZ\CircC;
use SVZ\CircularDependencyException;
use SVZ\ClassProvider;
use SVZ\Controller;
use SVZ\ControllerA;
use SVZ\Engine;
use SVZ\FastCar;
use SVZ\Injector;
use SVZ\ProviderNotFoundException;
use SVZ\ZendEngine;

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
        $injector->addProviders([Engine::class => ClassProvider::init(Engine::class)]);
        try {
            /** @var Engine $engine */
            $engine = $injector->get(Engine::class);
            $this->assertInstanceOf(Engine::class, $engine);
            $this->assertNotInstanceOf(ZendEngine::class, $engine);
        } catch (ProviderNotFoundException $e) {
            $this->assertTrue(false);
        }
    }

    public function testPolymorphism()
    {
        // Arrange
        $injector = new Injector(null);
        $injector->addProviders([Engine::class => ClassProvider::init(ZendEngine::class)]);
        try {
            /** @var Engine $engine */
            $engine = $injector->get(Engine::class);
            $this->assertInstanceOf(Engine::class, $engine);
            $this->assertInstanceOf(ZendEngine::class, $engine);
        } catch (ProviderNotFoundException $e) {
            $this->assertTrue(false);
        }
    }

    public function testMagicGet()
    {
        // Arrange
        $injector = new Injector(null);
        $injector->addProviders([Engine::class => ClassProvider::init(Engine::class)]);
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
        // Arrange
        $injector = new Injector(null);
        $injector->addProviders([Engine::class => ClassProvider::init(Engine::class)]);
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
        $injector->addProviders([Engine::class => ClassProvider::init(Engine::class, false)]);

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
        $injector->addProviders([
            Car::class => ClassProvider::init(FastCar::class),
            Engine::class => ClassProvider::init(Engine::class)
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


    }

    public function testProviders()
    {
        // Arrange
        $injector = new Injector(null);
        $injector->addProviders([
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
            $injector->addProviders([
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
            $injector->addProviders([
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


}