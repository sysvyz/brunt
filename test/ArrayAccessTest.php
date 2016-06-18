<?php
/**
 * Created by PhpStorm.
 * User: mb
 * Date: 22.05.16
 * Time: 09:38
 */

namespace BruntTest;


use Brunt\Injector;
use Brunt\Provider\Classes\ClassProvider;
use Brunt\Provider\Lazy\LazyProxyBuilder;
use BruntTest\Testobjects\ArrayAccessTestObject;

class ArrayAccessTest extends \PHPUnit_Framework_TestCase
{
    public function testArrayAccessTestObject(){

        $arr = ArrayAccessTestObject::init(['a' =>'A','B'=>'b']);


        $this->assertTrue(isset($arr['a']));
        $this->assertEquals($arr['a'],'A' );
        $this->assertTrue(isset($arr['B']));
        $this->assertEquals($arr['B'],'b' );
        unset($arr['a']);
        $this->assertFalse(isset($arr['a']));
        $arr['a'] = 'C';
        $this->assertTrue(isset($arr['a']));
        $this->assertEquals($arr['a'],'C' );
    }


    public function testProxyArrayAccess()
    {
        $injector = new Injector(null);
        $provider = ClassProvider::init(ArrayAccessTestObject::class);
        $builder = LazyProxyBuilder::init();
        /** @var ArrayAccessTestObject $arr */
        $arr = $builder->create($injector, $provider);


        $arr->hydrate(['a' =>'A','B'=>'b']);
        
        $testFunction = function (ArrayAccessTestObject $a) {
            $this->assertInstanceOf(ArrayAccessTestObject::class, $a);
            return true;
        };
        $testFunction2 = function (\ArrayAccess $a) {
            $this->assertInstanceOf(\ArrayAccess::class, $a);
            return true;
        };

        $this->assertTrue($testFunction($arr));
        $this->assertTrue($testFunction2($arr));
        ProxyTest::_isProxyTrait($arr);

        $this->assertTrue(isset($arr['a']));
        $this->assertEquals($arr['a'],'A' );
        $this->assertTrue(isset($arr['B']));
        $this->assertEquals($arr['B'],'b' );
        unset($arr['a']);
        $this->assertFalse(isset($arr['a']));
        $arr['a'] = 'C';
        $this->assertTrue(isset($arr['a']));
        $this->assertEquals($arr['a'],'C' );
        unset($arr['B']);
        foreach ($arr as $value){
            $this->assertEquals('C',$value );
        }
        $arr['b'] = 'B';

        /** @var \ArrayIterator $iterator */
        $iterator = $arr->getIterator();
        $this->assertEquals('C',$iterator->current() );
        $iterator->next();
        $this->assertEquals('B',$iterator->current() );

    }


}