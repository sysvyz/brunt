<?php
namespace BruntTest;

use Brunt\Binding;

use function Brunt\bind;

use Brunt\Injector;
use Brunt\Provider\Classes\ClassProvider;
use Brunt\Provider\FactoryProvider;
use Brunt\Provider\Lazy\LazyProvider;
use Brunt\Provider\ValueProvider;
use BruntTest\Testobjects\Engine;
use BruntTest\Testobjects\HeavyTire;
use BruntTest\Testobjects\Tire;



class BindingTest extends \PHPUnit_Framework_TestCase
{

    public function testBinding()
    {
        $binding = new Binding(Tire::class);
        $binding->toClass(Tire::class);
        $provider = $binding->getProvider();

        $this->assertEquals($binding->getToken(), Tire::class);
        $this->assertInstanceOf(ClassProvider::class, $provider);


    }


    public function testClassProvider()
    {
        $injector = new Injector(null);

        $binding = new Binding(Tire::class);

        $binding->toClass(Tire::class);

        $provider = $binding->getProvider();
        $entity = $provider->get($injector);

        $this->assertInstanceOf(ClassProvider::class, $provider);
        $this->assertInstanceOf(Tire::class, $entity);
    }
    public function testClassProvider2()
    {
        $injector = new Injector(null);

        $binding = new Binding(Tire::class);

        $binding->toClass(HeavyTire::class);

        $provider = $binding->getProvider();
        $entity = $provider->get($injector);

        $this->assertInstanceOf(ClassProvider::class, $provider);
        $this->assertInstanceOf(HeavyTire::class, $entity);
        $this->assertInstanceOf(Tire::class, $entity);
    }

    public function testLazyClassProvider()
    {
        $injector = new Injector(null);

        $binding = new Binding(Tire::class);

        $binding->toClass(Tire::class)->lazy();

        $provider = $binding->getProvider();
        $entity = $provider->get($injector);

        $this->assertInstanceOf(LazyProvider::class, $provider);
        $this->assertInstanceOf(Tire::class, $entity);
    }

    public function testValueProvider()
    {
        $injector = new Injector(null);

        $binding = new Binding(Tire::class);

        $value = new Tire();
        $binding->toValue($value);
        $provider = $binding->getProvider();
        $entity = $provider->get($injector);

        $this->assertInstanceOf(ValueProvider::class, $provider);
        $this->assertInstanceOf(Tire::class, $entity);

        $this->assertSame($value, $entity);
    }

    public function testValueProviderWithFunction()
    {
        $injector = new Injector(null);

        $binding = new Binding('a');
        $y = 3;
        $func = function ($x) use ($y) {
            return $x * $y;
        };
        $binding->toValue($func);
        $provider = $binding->getProvider();
        $injectedFunc = $provider->get($injector);
        $this->assertInstanceOf(ValueProvider::class, $provider);
        $this->assertInstanceOf(\Closure::class, $injectedFunc);
        $this->assertEquals($injectedFunc(5), 15);
    }

    public function testFactoryProvider()
    {
        $injector = new Injector(null);

        $binding = new Binding(Tire::class);

        $value = new Tire();
        $binding->toFactory(function (Injector $injector) use ($value) {
            return $value;
        });
        $provider = $binding->getProvider();
        $entity = $provider->get($injector);

        $this->assertInstanceOf(FactoryProvider::class, $provider);
        $this->assertInstanceOf(Tire::class, $entity);

        $this->assertSame($value, $entity);
    }

    public function testCallStatic()
    {

        $className = Engine::class;
        $binding = Binding::$className();
        $binding->toClass($className);
        $provider = $binding->getProvider();
        $engine = $provider->get(new Injector());
        $this->assertInstanceOf(Engine::class, $engine);

    }

    public function testClassFactoryProvider()
    {
        $injector = new Injector(null);

        $binding = new Binding(Tire::class);

        $binding->toClass(function (Injector $injector) {
            return new HeavyTire();
        })->lazy();

        $provider = $binding->getProvider();
        $entity = $provider->get($injector);

        $this->assertInstanceOf(LazyProvider::class, $provider);
        $this->assertInstanceOf(Tire::class, $entity);

        ProxyTest::_isProxyTrait($entity);
    }


    public function testBindingFuunfctionProvider()
    {
        $injector = new Injector(null);

        $injector(bind(HeavyTire::class));
        $entity = $injector->{HeavyTire::class};
        $this->assertInstanceOf(Tire::class, $entity);
        $this->assertInstanceOf(HeavyTire::class, $entity);
    }

}