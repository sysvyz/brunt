<?php
/**
 * Created by PhpStorm.
 * User: mb
 * Date: 19.05.16
 * Time: 20:15
 */

namespace BruntTest\Testobjects;


class MethodReflectionTestObject
{


    private $pri = 409;
    protected $pro = 586;
    public $pub = 653;

    /**
     * @return int
     */
    public function getPri()
    {
        return $this->pri;
    }

    /**
     * @return int
     */
    public function getPro()
    {
        return $this->pro;
    }

    /**
     * @return int
     */
    public function getPub()
    {
        return $this->pub;
    }



    /**
     * MethodReflectionTestObject constructor.
     */
    public function __construct()
    {
    }


    public function methodWithParam($param)
    {
        return [$param,5];
    }

    public function methodWithTypedParam(Engine $engine = null)
    {

    }

    public function methodWithParams($param1, $param2)
    {
        return $param2.' '.$param1;
    }
    public function methodWithOptionalParams($param1 = 'welt', $param2 = 'hello')
    {
        return $param2.' '.$param1;
    }

    public function methodWithTypedParams(Engine $engine, string $tire)
    {

    }

    private function privateMethodWithParam($param)
    {

    }

    private function privateMethodWithTypedParam(Engine $engine)
    {

    }

    private function privateMethodWithParams($param1, $param2)
    {

    }

    private function privateMethodWithTypedParams(Engine $engine, SmallTire $tire)
    {

    }

    private function privateMethod()
    {
        return 'private';
    }
    private static function privateStaticMethod()
    {

    }
    public function publicMethod()
    {
        return 'publicMethod';
    }
    function defaultMethod()
    {

    }
    public static function publicStaticMethod()
    {

    }

    function __call($name, $arguments)
    {

        return '__call:'.$name;
    }

    function __get($name)
    {
        return '__get:'.$name;
    }

    function __toString()
    {
        return "_TO_STRING_";
    }

    function __invoke()
    {
        // TODO: Implement __invoke() method.
    }


}