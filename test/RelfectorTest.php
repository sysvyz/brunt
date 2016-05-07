<?php

use Prescription\Provider\Reflector;
use PrescriptionTest\Testobjects\Car;
use PrescriptionTest\Testobjects\ProviderTestObject;
use PrescriptionTest\Testobjects\ProviderTestObjectB;
use PrescriptionTest\Testobjects\ProviderTestObjectC;


/**
 * Created by PhpStorm.
 * User: mb
 * Date: 17.04.16
 * Time: 15:35
 */
class ReflectorTest extends PHPUnit_Framework_TestCase
{

    public function testReflector()
    {
        $ref = new Reflector(Car::class);
        $this->assertInstanceOf(Reflector::class, $ref);
    }
    public function testHasProvider()
    {
        $ref1 = new Reflector(ProviderTestObject::class);
        $this->assertTrue($ref1->hasProviders());

        $ref2 = new Reflector(ProviderTestObjectB::class);
        $this->assertFalse($ref2->hasProviders());

        $ref3 = new Reflector(ProviderTestObjectC::class);
        $this->assertTrue($ref3->hasProviders());
    }

    public function testResolve()
    {
        $ref1 = new Reflector(Car::class);
    }


}