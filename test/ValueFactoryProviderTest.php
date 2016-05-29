<?php
/**
 * Created by PhpStorm.
 * User: mb
 * Date: 29.05.16
 * Time: 23:41
 */

namespace BruntTest;


use Brunt\Injector;
use Brunt\Provider\Lazy\LazyProxyObject;
use Brunt\Provider\ValueFactoryProvider;
use Brunt\Provider\ValueProvider;
use BruntTest\Testobjects\Tire;

class ValueFactoryProviderTest extends \PHPUnit_Framework_TestCase
{

    public function testProviders()
    {
        $injector = new Injector(null);
        $injector->providers([
            'tire' => ValueFactoryProvider::init(function (){
                return new Tire();
            })

        ]);
        $this->assertEquals($injector->{'tire'}->type, 'Tire');
        $this->assertInstanceOf(Tire::class,$injector->{'tire'});
    }

    public function testProvidersLazy()
    {
        $injector = new Injector(null);
        $injector->providers([
            'tire' => ValueFactoryProvider::init(function (){
                return new Tire();
            })->lazy()

        ]);
        $this->assertEquals($injector->{'tire'}->type, 'Tire');
        $this->assertNotInstanceOf(Tire::class,$injector->{'tire'});
        $this->assertInstanceOf(LazyProxyObject::class,$injector->{'tire'});
        $this->assertInstanceOf(Tire::class,$injector->{'tire'}->getInstance());
        $this->assertNotSame($injector->{'tire'}->getInstance(),$injector->{'tire'}->getInstance());
    }
    public function testProvidersSingleton()
    {
        $injector = new Injector(null);
        $injector->providers([
            'tire' => ValueFactoryProvider::init(function (){
                return new Tire();
            })->singleton()

        ]);
        $this->assertEquals($injector->{'tire'}->type, 'Tire');

        $injector->{'tire'}->a = "a";

        $this->assertEquals($injector->{'tire'}->a, 'a');
        $this->assertInstanceOf(Tire::class,$injector->{'tire'});
        $this->assertSame($injector->{'tire'},$injector->{'tire'});
    }
    public function testProvidersLazySingleton()
    {
        $injector = new Injector(null);
        $injector->providers([
            'tire' => ValueFactoryProvider::init(function (){
                return new Tire();
            })->lazy()->singleton()

        ]);
        $this->assertEquals($injector->{'tire'}->type, 'Tire');
        $injector->{'tire'}->a = "a";

        $this->assertEquals($injector->{'tire'}->a, 'a');
        $this->assertNotSame($injector->{'tire'},$injector->{'tire'});
        $this->assertNotInstanceOf(Tire::class,$injector->{'tire'});
        $this->assertInstanceOf(LazyProxyObject::class,$injector->{'tire'});
        $this->assertInstanceOf(Tire::class,$injector->{'tire'}->getInstance());
        $this->assertSame($injector->{'tire'}->getInstance(),$injector->{'tire'}->getInstance());
    }
    public function testProvidersSingletonLazy()
    {
        $injector = new Injector(null);
        $injector->providers([
            'tire' => ValueFactoryProvider::init(function (){
                return new Tire();
            })->lazy()->singleton()

        ]);
        $this->assertEquals($injector->{'tire'}->type, 'Tire');
        $injector->{'tire'}->a = "a";

        $this->assertEquals($injector->{'tire'}->a, 'a');
        $this->assertNotSame($injector->{'tire'},$injector->{'tire'});
        $this->assertNotInstanceOf(Tire::class,$injector->{'tire'});
        $this->assertInstanceOf(LazyProxyObject::class,$injector->{'tire'});
        $this->assertInstanceOf(Tire::class,$injector->{'tire'}->getInstance());
        $this->assertSame($injector->{'tire'}->getInstance(),$injector->{'tire'}->getInstance());
    }


}