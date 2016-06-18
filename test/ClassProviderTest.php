<?php
/**
 * Created by PhpStorm.
 * User: mb
 * Date: 21.05.16
 * Time: 04:08
 */

namespace BruntTest;


use Brunt\Exception\CircularDependencyException;
use Brunt\Exception\InjectableException;
use Brunt\Injector;
use Brunt\Provider\Classes\ClassFactoryProvider;
use Brunt\Provider\Classes\ClassProvider;
use Brunt\Provider\ValueFactoryProvider;
use Brunt\Provider\ValueProvider;
use BruntTest\Testobjects\CircA;
use BruntTest\Testobjects\CircB;
use BruntTest\Testobjects\CircC;
use BruntTest\Testobjects\Controller;
use BruntTest\Testobjects\ControllerA;
use BruntTest\Testobjects\Engine;
use BruntTest\Testobjects\NonInjectable;
use BruntTest\Testobjects\NonInjectableB;
use BruntTest\Testobjects\NonInjectableWrapper;

class ClassProviderTest extends \PHPUnit_Framework_TestCase
{


    public function testProviders()
    {
        // Arrange
        $injector = new Injector(null);
        $injector->providers([
            Controller::class => ClassProvider::init(ControllerA::class),
            '%BASE_URL%' => ValueFactoryProvider::init(function () {
                return 'http://sysvyz.org/';
            })
        ]);

        /** @var ControllerA $ctrl */
        $ctrl = $injector->get(Controller::class);

        $this->assertInstanceOf(ControllerA::class, $ctrl);
        $this->assertSame($ctrl->requestService, $ctrl->serviceZ->requestService);
        $this->assertNotSame($ctrl->requestService, $ctrl->serviceZ->serviceY->requestService);
        $this->assertNotEquals($ctrl->requestService->url, $ctrl->serviceZ->serviceY->requestService->url);

    }


    public function testProvidersLazy()
    {
        // Arrange
        $injector = new Injector(null);
        $injector->providers([
            Controller::class => ClassProvider::init(ControllerA::class)->lazy(),
            '%BASE_URL%' => ValueProvider::init('http://sysvyz.org/')
        ]);

        /** @var ControllerA $ctrl */
        $ctrl = $injector->get(Controller::class);

        $this->assertInstanceOf(ControllerA::class, $ctrl);
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

    public function testLazy()
    {
        // Arrange
        $injector = new Injector(null);

        $injector->providers([
            Engine::class => ClassProvider::init(Engine::class)->lazy(),
        ]);

        $proxy = $injector->get(Engine::class);

        $this->assertInstanceOf(Engine::class, $proxy->getInstance());

    }

    public function testLazySingleton()
    {
        // Arrange
        $injector = new Injector(null);

        $injector->providers([
            Engine::class => ClassProvider::init(Engine::class)->lazy()->singleton(),
        ]);

        $proxy2 = $injector->get(Engine::class);
        $proxy1 = $injector->get(Engine::class);

        $this->assertInstanceOf(Engine::class, $proxy1->getInstance());
        $this->assertInstanceOf(Engine::class, $proxy2->getInstance());
        $this->assertNotSame($proxy1, $proxy2);
        $this->assertSame($proxy1->getInstance(), $proxy2->getInstance());

    }

    public function testSingletonLazy()
    {
        // Arrange
        $injector = new Injector(null);

        $injector->providers([
            Engine::class => ClassProvider::init(Engine::class)->singleton()->lazy(),
        ]);

        $proxy2 = $injector->get(Engine::class);
        $proxy1 = $injector->get(Engine::class);

        $this->assertInstanceOf(Engine::class, $proxy1->getInstance());
        $this->assertInstanceOf(Engine::class, $proxy2->getInstance());
        $this->assertNotSame($proxy1, $proxy2);
        $this->assertSame($proxy1->getInstance(), $proxy2->getInstance());

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
                NonInjectable::class => ClassFactoryProvider::init(NonInjectable::class, function () {
                    return new NonInjectable(5);
                }),
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
        } catch (InjectableException $e) {
            $this->assertTrue(true);
        }
    }


}