<?php
/**
 * Created by PhpStorm.
 * User: mb
 * Date: 20.05.16
 * Time: 02:26
 */

namespace BruntTest;


use Brunt\Exception\ProxyBuildErrorException;
use Brunt\Injector;
use Brunt\Provider\ClassProvider;
use Brunt\Provider\Lazy\LazyProxyBuilder;
use Brunt\Provider\Lazy\ProxyRenderer;
use Brunt\Reflection\Reflector;
use BruntTest\Testobjects\MethodReflectionTestObject;
use BruntTest\Testobjects\ProxyTestProxy;
use PHPUnit_Framework_TestCase;

class ProxyTest extends PHPUnit_Framework_TestCase
{


    public function testProxy()
    {
        $b = new  ProxyTestProxy();

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

        $this->assertSame($proxy->getPri(), 409);
        $this->assertSame($proxy->privateMethod(), "__call:privateMethod"); //private cant be called, call -> __call instead
        $this->assertSame($proxy->publicMethod(), 'publicMethod');
        $this->assertSame($proxy . "", '_TO_STRING_');

    }


    public function testProxyBuilder()
    {
        $injector = new Injector(null);
        $provider = ClassProvider::init(MethodReflectionTestObject::class);
        $builder = new LazyProxyBuilder();
        /** @var MethodReflectionTestObject $proxy */
        $proxy = $builder->create($injector, $provider);
        

        $testFunction = function (MethodReflectionTestObject $a){
            $this->assertInstanceOf(MethodReflectionTestObject::class,$a);
            $this->assertSame($a->getPri(), 409);
            $this->assertSame($a->privateMethod(), "__call:privateMethod"); //private cant be called, call -> __call instead
            $this->assertSame($a->publicMethod(), 'publicMethod');
            $this->assertSame($a . "", '_TO_STRING_');
        };

        $testFunction($proxy);

    }



}
