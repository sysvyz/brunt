<?php
/**
 * Created by PhpStorm.
 * User: mb
 * Date: 29.05.16
 * Time: 23:41
 */

namespace BruntTest;


use Brunt\Injector;
use Brunt\Provider\ValueProvider;

class ValueProviderTest extends \PHPUnit_Framework_TestCase
{

    public function testProviders()
    {
        // Arrange
        $injector = new Injector(null);
        $injector->providers([
            'BASE_URL' => ValueProvider::init('http://sysvyz.org/')

        ]);
        $this->assertEquals($injector->{'BASE_URL'}, 'http://sysvyz.org/');
    }

    public function testProvidersLazy()
    {
        // Arrange
        $injector = new Injector(null);
        $injector->providers([
            'BASE_URL' => ValueProvider::init('http://sysvyz.org/')->lazy()

        ]);
        $this->assertEquals($injector->{'BASE_URL'}, 'http://sysvyz.org/');
    }
    public function testProvidersSingleton()
    {
        // Arrange
        $injector = new Injector(null);
        $injector->providers([
            'BASE_URL' => ValueProvider::init('http://sysvyz.org/')->singleton()

        ]);
        $this->assertEquals($injector->{'BASE_URL'}, 'http://sysvyz.org/');
    }
    public function testProvidersLazySingleton()
    {
        // Arrange
        $injector = new Injector(null);
        $injector->providers([
            'BASE_URL' => ValueProvider::init('http://sysvyz.org/')->lazy()->singleton()

        ]);
        $this->assertEquals($injector->{'BASE_URL'}, 'http://sysvyz.org/');
    }
    public function testProvidersSingletonLazy()
    {
        // Arrange
        $injector = new Injector(null);
        $injector->providers([
            'BASE_URL' => ValueProvider::init('http://sysvyz.org/')->singleton()->lazy()
        ]);
        $this->assertEquals($injector->{'BASE_URL'}, 'http://sysvyz.org/');
    }


}