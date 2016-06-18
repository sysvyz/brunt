<?php

namespace BruntTest;
use Brunt\Reflection\Reflector;
use Brunt\Reflection\ReflectorFactory;
use BruntTest\Testobjects\Car;
use BruntTest\Testobjects\MethodReflectionTestObject;
use BruntTest\Testobjects\ProviderTestObject;
use BruntTest\Testobjects\ProviderTestObjectB;
use BruntTest\Testobjects\ProviderTestObjectC;


class ReflectorTest extends \PHPUnit_Framework_TestCase
{

    public function testReflector()
    {
        $ref = ReflectorFactory::buildReflectorByClassName(Car::class);
        $this->assertInstanceOf(Reflector::class, $ref);
    }

    public function testHasProvider()
    {
        $ref1 = ReflectorFactory::buildReflectorByClassName(ProviderTestObject::class);
        $this->assertTrue($ref1->hasProviders());

        $ref2 = ReflectorFactory::buildReflectorByClassName(ProviderTestObjectB::class);
        $this->assertFalse($ref2->hasProviders());

        $ref3 =  ReflectorFactory::buildReflectorByClassName(ProviderTestObjectC::class);
        $this->assertTrue($ref3->hasProviders());
    }

    public function testResolve()
    {
        $ref1 = ReflectorFactory::buildReflectorByClassName(Car::class);
    }

    public function testReflectionGetMethods()
    {
        $ref1 = ReflectorFactory::buildReflectorByClassName(MethodReflectionTestObject::class);
        $class = $ref1->getCompactReferenceClass();

        $this->assertSame($class->getClassName(),MethodReflectionTestObject::class );
        
    }


}


