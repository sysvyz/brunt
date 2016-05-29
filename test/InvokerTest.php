<?php
/**
 * Created by PhpStorm.
 * User: mb
 * Date: 29.05.16
 * Time: 18:48
 */

namespace BruntTest;


use Brunt\Injector;
use Brunt\Reflection\Invoker;

class InvokerTest extends \PHPUnit_Framework_TestCase
{

    /**
     *
     */
    public function testInvoker()
    {
        $injector = new Injector();

        $injector->provide('%VALUE%',function(){return 5;});

        $obj = $injector->{InvokableObject::class};

        $invoker = new Invoker($injector);

        $res = $invoker->invoke($obj, 'execute');

        $this->assertEquals($res,30);

    }


}


class InjectedParamObject
{
    public $val = 2;
}

class InvokableObject
{

    /**
     * @param InjectedParamObject $injectedObject
     * @param $value
     * @dependency $value %VALUE%
     * @return int
     */
    public function execute(InjectedParamObject $injectedObject,$value)
    {
        return 3 * $injectedObject->val*$value;
    }

    public static function _DI_DEPENDENCIES()
    {
        return [
            'execute.value' => "%VALUE%"
        ];
    }

}