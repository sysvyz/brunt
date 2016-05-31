<?php


namespace BruntTest;


use Brunt\Injector;
use Brunt\Provider\Classes\ClassProvider;
use Brunt\Provider\Lazy\LazyProxyBuilder;
use Brunt\Provider\Lazy\LazyProxyObject;
use Brunt\Provider\Lazy\ProxyRenderer;
use Brunt\Provider\Lazy\T\ProxyTrait;
use Brunt\Provider\ValueFactoryProvider;
use Brunt\Reflection\Reflector;
use BruntTest\Testobjects\Car;
use BruntTest\Testobjects\MethodReflectionTestObject;
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
        $ref1 = new Reflector(MethodReflectionTestObject::class);
        $renderer = new ProxyRenderer($ref1, 'RandomProxyName_ds8bfgFHGTG4');

        $renderedClass = $renderer->render();

        $injector = new Injector(null);
        $provider = ClassProvider::init(MethodReflectionTestObject::class);

        /** @var MethodReflectionTestObject $proxy */
        $proxy = null;

        eval($renderedClass . '$proxy = new RandomProxyName_ds8bfgFHGTG4($provider, $injector);');
        $this->assertInstanceOf(MethodReflectionTestObject::class, $proxy);

    }

    public function testProxyBuilder(){
        $builder =  new LazyProxyBuilder();
        $this->assertEquals('BruntTest_Testobjects_Car_Brunt_Proxy',$builder->proxifyClassName(Car::class));
    }

    public function testProxyByBuilder()
    {
        $injector = new Injector(null);
        $provider = ClassProvider::init(MethodReflectionTestObject::class);
        $builder = new LazyProxyBuilder();
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
        $proxy = $provider($injector);


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
        $proxy = $provider($injector);
        $testFunction = function (LazyProxyObject $a) {
            $this->assertInstanceOf(LazyProxyObject::class, $a);
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


}
