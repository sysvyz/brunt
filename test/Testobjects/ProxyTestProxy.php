<?php


namespace BruntTest\Testobjects;


class ProxyTestProxy
{
    private $i;

    /**
     * B constructor.
     * @param $i
     */
    public function __construct()
    {
        $this->i = new ProxyTestConcrete();

        unset($this->var);


        $this->i->var = 4;
    }

    function __get($name)
    {
        return $this->i->$name;
    }
}