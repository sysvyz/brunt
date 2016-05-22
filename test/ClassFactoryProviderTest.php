<?php
/**
 * Created by PhpStorm.
 * User: mb
 * Date: 21.05.16
 * Time: 04:08
 */

namespace BruntTest;


use Brunt\Binding;
use Brunt\Injector;
use Brunt\Provider\ClassFactoryProvider;
use Brunt\Provider\Lazy\ProxyTrait;
use BruntTest\Testobjects\Engine;
use BruntTest\Testobjects\HeavyEngine;

class ClassFactoryProviderTest extends \PHPUnit_Framework_TestCase
{


    public function testProviders()
    {
        // Arrange
        $injector = new Injector(null);
        $injector->providers([
            Engine::class => ClassFactoryProvider::init(Engine::class,function (){
                return new Engine();
            })

        ]);

        /** @var Engine $engine */
        $engine = $injector->get(Engine::class);
        $testFunction = function (Engine $engine){
            return true;
        };

        $this->assertTrue($testFunction($engine));
        $this->assertInstanceOf(Engine::class,$engine);
        $this->assertEquals("Engine",$engine->type);
        ProxyTest::_isNotProxyTrait($engine);

    }


    public function testProviderLazy()
    {
        // Arrange
        $injector = new Injector(null);
        $injector->providers([
            Engine::class => ClassFactoryProvider::init(Engine::class,function (){
                return new Engine();
            })->lazy()

        ]);

        /** @var Engine $engine */
        $engine = $injector->get(Engine::class);
        $testFunction = function (Engine $engine){
            return true;
        };

        $this->assertTrue($testFunction($engine));
        $this->assertInstanceOf(Engine::class,$engine);
        $this->assertEquals("Engine",$engine->type);
        ProxyTest::_isProxyTrait($engine);
    }

    public function testProviderLazyInheritance()
    {
        // Arrange
        $injector = new Injector(null);
        $injector->providers([
            Engine::class => ClassFactoryProvider::init(Engine::class,function (){
                return new HeavyEngine();
            })->lazy()

        ]);

        /** @var Engine $engine */
        $engine = $injector->get(Engine::class);
        $testFunction = function (Engine $engine){
            return true;
        };

        $this->assertTrue($testFunction($engine));
        $this->assertInstanceOf(Engine::class,$engine);
        $this->assertEquals("HeavyEngine",$engine->type);
        ProxyTest::_isProxyTrait($engine);
    }

    public function testProviderInheritanceLazy()
    {
        // Arrange
        $injector = new Injector(null);
        $injector->{Engine::class}(ClassFactoryProvider::init(HeavyEngine::class,function (){
                return new Engine();
            })->lazy()
        );

        /** @var Engine $engine */
        $engine = $injector->get(Engine::class);
        $testFunction = function (Engine $engine){
            return true;
        };

        $this->assertTrue($testFunction($engine));
        $this->assertInstanceOf(Engine::class,$engine);
        $this->assertEquals("Engine",$engine->type);
        ProxyTest::_isProxyTrait($engine);
    }
    public function testBindingInheritanceLazy()
    {
        // Arrange
        $injector = new Injector(null);
        $injector->bind([
            Binding::init(Engine::class)->toFactory(function (){
                return new Engine();
            })->lazy()

        ]);

        /** @var Engine $engine */
        $engine = $injector->get(Engine::class);
        $testFunction = function (Engine $engine){
            return true;
        };


        $this->assertTrue($testFunction($engine));
        $this->assertInstanceOf(Engine::class,$engine);
        $this->assertEquals("Engine",$engine->type);

        ProxyTest::_isProxyTrait($engine);
    }

   
}