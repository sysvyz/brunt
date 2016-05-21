<?php

namespace BruntTest;
use Brunt\Reflection\Reflector;
use BruntTest\Testobjects\Car;
use BruntTest\Testobjects\MethodReflectionTestObject;
use BruntTest\Testobjects\ProviderTestObject;
use BruntTest\Testobjects\ProviderTestObjectB;
use BruntTest\Testobjects\ProviderTestObjectC;


class ReflectorTest extends \PHPUnit_Framework_TestCase
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

    public function testReflectionGetMethods()
    {
        $ref1 = new Reflector(MethodReflectionTestObject::class);
        $class = $ref1->getCompactReferenceClass();

        $this->assertSame($class->getClassName(),MethodReflectionTestObject::class );
        
    }


}


