<?php
use Brunt\Injector;
use Brunt\Binding;
use Brunt\Provider\ClassProvider;
use Brunt\Provider\FactoryProvider;
use Brunt\Provider\ValueProvider;
use BruntTest\Testobjects\Car;
use BruntTest\Testobjects\Engine;
use BruntTest\Testobjects\Tire;


/**
 * Created by PhpStorm.
 * User: mb
 * Date: 17.04.16
 * Time: 15:35
 */
class BindingTest extends PHPUnit_Framework_TestCase
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
        $entity = $provider($injector);

        $this->assertInstanceOf(ClassProvider::class, $provider);
        $this->assertInstanceOf(Tire::class, $entity);
    }

    public function testValueProvider()
    {
        $injector = new Injector(null);

        $binding = new Binding(Tire::class);

        $value = new Tire();
        $binding->toValue($value);
        $provider = $binding->getProvider();
        $entity = $provider($injector);

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
        $injectetFunc = $provider($injector);
        $this->assertInstanceOf(ValueProvider::class, $provider);
        $this->assertInstanceOf(Closure::class, $injectetFunc);
        $this->assertEquals($injectetFunc(5), 15);
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
        $entity = $provider($injector);

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
        $engine = $provider(new Injector());
        $this->assertInstanceOf(Engine::class, $engine);

    }

}