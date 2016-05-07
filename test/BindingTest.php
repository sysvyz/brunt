<?php
use Brunt\Injector;
use Brunt\Provider\Binding;
use Brunt\Provider\ClassProvider;
use Brunt\Provider\FactoryProvider;
use Brunt\Provider\ValueProvider;
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


        $this->assertEquals($binding->getToken(),Tire::class);
        $this->assertInstanceOf(ClassProvider::class,$provider);


    }


    public function testClassProvider()
    {
        $injector = new Injector(null);

        $binding = new Binding(Tire::class);

        $binding->toClass(Tire::class);
        $provider = $binding->getProvider();
        $entity = $provider($injector);

        $this->assertInstanceOf(ClassProvider::class,$provider);
        $this->assertInstanceOf(Tire::class,$entity);
    }

    public function testValueProvider()
    {
        $injector = new Injector(null);

        $binding = new Binding(Tire::class);

        $value =new Tire();
        $binding->toValue($value);
        $provider = $binding->getProvider();
        $entity = $provider($injector);

        $this->assertInstanceOf(ValueProvider::class,$provider);
        $this->assertInstanceOf(Tire::class,$entity);

        $this->assertSame($value,$entity);
    }


    public function testFactoryProvider()
    {
        $injector = new Injector(null);

        $binding = new Binding(Tire::class);

        $value =new Tire();
        $binding->toFactory(function(Injector $injector) use ($value){
            return $value;
        });
        $provider = $binding->getProvider();
        $entity = $provider($injector);

        $this->assertInstanceOf(FactoryProvider::class,$provider);
        $this->assertInstanceOf(Tire::class,$entity);

        $this->assertSame($value,$entity);
    }


}