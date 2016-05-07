<?php
use Prescription\Injector;
use Prescription\Provider\Rmk\ClassProvider;
use PrescriptionTest\Testobjects\Car;
use PrescriptionTest\Testobjects\Engine;


/**
 * Created by PhpStorm.
 * User: mb
 * Date: 17.04.16
 * Time: 15:35
 */
class ClassProviderRmkTest extends PHPUnit_Framework_TestCase
{

    public function testProvider()
    {
        $p = new ClassProvider(Car::class);
        $i = new Injector();
        $i->provide(Engine::class,ClassProvider::init(Engine::class));
        $r = $p($i);
        print_r($r);

    }


}