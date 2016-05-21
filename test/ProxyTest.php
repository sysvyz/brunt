<?php


namespace BruntTest;


use Brunt\Injector;
use Brunt\Provider\ClassProvider;
use Brunt\Provider\Lazy\LazyProxyBuilder;
use Brunt\Provider\Lazy\ProxyRenderer;
use Brunt\Reflection\Reflector;
use BruntTest\Testobjects\MethodReflectionTestObject;
use PHPUnit_Framework_TestCase;

class ProxyTest extends PHPUnit_Framework_TestCase
{


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


    public function testProxyBuilder()
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
        $this->assertSame($proxy->getPri(), 409);
        $this->assertSame($proxy->privateMethod(), "__call:privateMethod"); //private cant be called, call -> __call instead
        $this->assertSame($proxy->publicMethod(), 'publicMethod');
        $this->assertSame($proxy->publicMethodWithoutModifier(), 'publicMethodWithoutModifier');
        $this->assertSame($proxy . "", '_TO_STRING_');
        
        $this->assertTrue($testFunction($proxy));

    }


}
