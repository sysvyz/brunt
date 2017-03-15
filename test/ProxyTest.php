<?php


namespace BruntTest;


use Brunt\Injector;
use Brunt\Provider\Classes\ClassProvider;
use Brunt\Provider\Lazy\LazyProxyBuilder;
use Brunt\Provider\Lazy\LazyProxyObject;
use Brunt\Provider\Lazy\ProxyRenderer;
use Brunt\Provider\Lazy\Mixin\ProxyTrait;
use Brunt\Provider\ValueFactoryProvider;
use Brunt\Provider\ValueProvider;
use Brunt\Reflection\ReflectorFactory;
use BruntTest\Testobjects\Car;
use BruntTest\Testobjects\ControllerA;
use BruntTest\Testobjects\MethodReflectionTestObject;
use BruntTest\Testobjects\Request;
use BruntTest\Testobjects\RequestService;
use BruntTest\Testobjects\ServiceY;
use BruntTest\Testobjects\ServiceZ;
use PHPUnit_Framework_TestCase;

class ProxyTest extends PHPUnit_Framework_TestCase
{

    public static function _isProxyTrait($proxy)
    {
        $r = new \ReflectionClass($proxy);
        self::assertFalse(empty(array_intersect([ProxyTrait::class], $r->getTraitNames())));
    }

    public static function _isNotProxyTrait($proxy)
    {
        $r = new \ReflectionClass($proxy);
        self::assertTrue(empty(array_intersect([ProxyTrait::class], $r->getTraitNames())));
    }


    public function testProxyRenderer()
    {

        $ref1 = ReflectorFactory::buildReflectorByClassName(MethodReflectionTestObject::class);
        $crclass = $ref1->getCompactReferenceClass();


        $renderer = new ProxyRenderer($crclass, 'RandomProxyName_ds8bfgFHGTG4');

        $renderedClass = $renderer->render();

        $injector = new Injector(null);
        $provider = ClassProvider::init(MethodReflectionTestObject::class);

        /** @var MethodReflectionTestObject $proxy */
        $proxy = null;

        eval($renderedClass . '');

        /** @noinspection PhpUnnecessaryFullyQualifiedNameInspection */
        $proxy = new \Brunt\ProxyObject\RandomProxyName_ds8bfgFHGTG4($provider, $injector);
        $this->assertInstanceOf(MethodReflectionTestObject::class, $proxy);

    }

    public function testProxyBuilder()
    {
        $builder = LazyProxyBuilder::init();
        $this->assertEquals('BruntTest_Testobjects_Car_Brunt_Proxy', $builder->getProxyClassName(Car::class));
    }

    public function testProxyByBuilder()
    {
        $injector = new Injector(null);
        $provider = ClassProvider::init(MethodReflectionTestObject::class);
        $builder = LazyProxyBuilder::init();
        /** @var MethodReflectionTestObject $proxy */
        $proxy = $builder->create($injector, $provider);

        $testFunction = function (MethodReflectionTestObject $a) {
            $this->assertInstanceOf(MethodReflectionTestObject::class, $a);
            return true;
        };
        $this->assertEquals($proxy->getPri(), 409);
        $this->assertEquals($proxy->privateMethod(), "__call:privateMethod"); //private cant be called, call -> __call instead
        $this->assertEquals($proxy->publicMethod(), 'publicMethod');
        $this->assertEquals($proxy->publicMethodWithoutModifier(), 'publicMethodWithoutModifier');
        $this->assertEquals($proxy . "", '_TO_STRING_');
        $this->assertEquals($proxy->getProvider(), 'PROVIDER');

        $this->assertTrue($testFunction($proxy));

    }

    public function testProxyByLazyMethod()
    {
        $injector = new Injector(null);
        $provider = ClassProvider::init(MethodReflectionTestObject::class)->lazy();

        /** @var MethodReflectionTestObject $proxy */
        $proxy = $provider->get($injector);


        $testFunction = function (MethodReflectionTestObject $a) {
            $this->assertInstanceOf(MethodReflectionTestObject::class, $a);
            return true;
        };
        $this->assertEquals($proxy->getPri(), 409);
        $this->assertEquals($proxy->privateMethod(), "__call:privateMethod"); //private cant be called, call -> __call instead
        $this->assertEquals($proxy->publicMethod(), 'publicMethod');
        $this->assertEquals($proxy->publicMethodWithoutModifier(), 'publicMethodWithoutModifier');
        $this->assertEquals($proxy . "", '_TO_STRING_');
        $this->assertEquals($proxy->getProvider(), 'PROVIDER');

        $this->assertTrue($testFunction($proxy));

    }

    public function testProxy()
    {
        $injector = new Injector(null);
        $provider = ValueFactoryProvider::init(function () {
            return new MethodReflectionTestObject();
        })->lazy();

        /** @var MethodReflectionTestObject $proxy */
        $proxy = $provider->get($injector);
        $testFunction = function (LazyProxyObject $a) {
            $this->assertInstanceOf(LazyProxyObject::class, $a);
            return true;
        };
        $this->assertEquals($proxy->getPri(), 409);
        $this->assertEquals($proxy->privateMethod(), "__call:privateMethod"); //private cant be called, calls -> __call instead
        $this->assertEquals($proxy->publicMethod(), 'publicMethod');
        $this->assertEquals($proxy->publicMethodWithoutModifier(), 'publicMethodWithoutModifier');
        $this->assertEquals($proxy . "", '_TO_STRING_');
        $this->assertEquals($proxy->getProvider(), 'PROVIDER');

        $this->assertTrue($testFunction($proxy));

    }

    public function testTimings()
    {
        $count = 10;
        $repeat = 10;

        $data = [];
        for ($i = 0; $i < $repeat; $i++) {
            $data  [] = [
                'buildLazyAndGetInstance' => $this->buildLazyAndGetInstance($count) * 1,
                'buildNormal' => $this->buildNormal($count) * 1,
                'buildSingleton' => $this->buildSingleton($count) * 1,
                'buildLazy' => $this->buildLazy($count) * 1,
                'buildVanilla' => $this->buildVanilla($count) * 1
            ];
        }
    }

    private function buildLazyAndGetInstance($count)
    {
        $injector = new Injector();
        $injector->provide(ControllerA::class, ClassProvider::init(ControllerA::class)->lazy());
        $injector->provide('%BASE_URL%', ValueProvider::init('%BASE_URL%'));
        $t = microtime(true);
        $arr = [];
        for ($i = 0; $i < $count; $i++) {
            $arr[] = $injector->get(ControllerA::class)->getInstance();
        }
        return microtime(true) - $t;
    }



    private function buildNormal($count)
    {
        $injector = new Injector();
        $injector->provide(ControllerA::class, ClassProvider::init(ControllerA::class));
        $injector->provide('%BASE_URL%', ValueProvider::init('%BASE_URL%'));

        $arr = [];
        $t = microtime(true);
        for ($i = 0; $i < $count; $i++) {
            $arr[] = $injector->get(ControllerA::class);
        }
        return microtime(true) - $t;
    }

    private function buildSingleton($count)
    {
        $injector = new Injector();
        $injector->provide(ControllerA::class, ClassProvider::init(ControllerA::class)->singleton());
        $injector->provide('%BASE_URL%', ValueProvider::init('%BASE_URL%'));

        $t = microtime(true);
        $arr = [];
        for ($i = 0; $i < $count; $i++) {
            $arr[] = $injector->get(ControllerA::class);
        }
        return microtime(true) - $t;
    }

    private function buildLazy($count)
    {
        $t = microtime(true);

        $injector = new Injector();
        $injector->provide(ControllerA::class, ClassProvider::init(ControllerA::class)->lazy());
        $injector->provide('%BASE_URL%', ValueProvider::init('%BASE_URL%'));

        $arr = [];
        for ($i = 0; $i < $count; $i++) {
            $arr[] = $injector->get(ControllerA::class);

        }
        return microtime(true) - $t;
    }

    private function buildVanilla($count)
    {
        $t = microtime(true);
        $arr = [];
        for ($i = 0; $i < $count; $i++) {
            $url1= 'a';
            $url2= 'b';
            $request = new Request();
            $rs = new RequestService($url1);
            $rs2 = new RequestService($url2);
            $sy = new ServiceY($rs2);
            $sz =   new ServiceZ($rs,$sy);
            $arr[] = new ControllerA($request,$rs, $sz);
        }
        return microtime(true) - $t;
    }


}
