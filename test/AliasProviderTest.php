<?php
/**
 * Created by PhpStorm.
 * User: mb
 * Date: 31.05.16
 * Time: 03:25
 */

namespace BruntTest;

use function \Brunt\bind;
use Brunt\Binding;
use Brunt\Injector;
use Brunt\Provider\AliasProvider;
use Brunt\Provider\Classes\ClassProvider;
use BruntTest\Testobjects\Engine;
use BruntTest\Testobjects\HeavyEngine;

class AliasProviderTest extends \PHPUnit_Framework_TestCase
{

    public function testAliasProvider()
    {
        $injector = new Injector(null);

        $injector->providers([
            Engine::class => ClassProvider::init(Engine::class),
            "AAA" => AliasProvider::init(Engine::class),
        ]);

        $engine = $injector->get('AAA');
        $this->assertInstanceOf(Engine::class,$engine);

    }


    public function testAliasProviderLazy()
    {
        $injector = new Injector(null);

        $injector->providers([
            Engine::class => ClassProvider::init(Engine::class)->lazy(),
            "AAA" => AliasProvider::init(Engine::class),
        ]);

        $engine = $injector->get('AAA');
        $this->assertInstanceOf(Engine::class,$engine);

    }



    public function testBinding()
    {
        $injector = new Injector(null);

        $injector->bind([
            Binding::init( HeavyEngine::class)->lazy(),
            bind("AAA")->toAlias(HeavyEngine::class),
        ]);

        $engine = $injector->get('AAA');
        $this->assertInstanceOf(Engine::class,$engine);

    }

}