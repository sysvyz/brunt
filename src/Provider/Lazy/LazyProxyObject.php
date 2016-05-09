<?php
/**
 * Created by PhpStorm.
 * User: mb
 * Date: 09.05.16
 * Time: 02:16
 */

namespace Brunt\Provider\Lazy;


use Brunt\Injector;
use Brunt\Provider\Provider;

class LazyProxyObject
{
    /**
     * @var Provider
     */
    private $provider;
    /**
     * @var mixed
     */
    private $instance;
    /**
     * @var Injector
     */
    private $injector;


    /**
     * LazyProxyTrait constructor.
     * @param Provider $provider
     * @param Injector $injector
     */
    public function __construct(Provider $provider, Injector $injector)
    {
        $this->provider = $provider;
        $this->injector = $injector;
        $this->instance = null;
    }

    public function __call($name, $arguments)
    {
        $this->getInstance()->$name(...$arguments);
    }

    public function __get($name)
    {
        return $this->getInstance()->$name;
    }

    public function __set($name, $value)
    {

        $this->getInstance()->$name = $value;
    }

    public function __toString()
    {
        return $this->getInstance().'';
    }

    function __isset($name)
    {

        return $this->getInstance()->__isset($name);    }

    function __unset($name)
    {
        return $this->getInstance()->__unset($name);
    }


    function __invoke(...$args)
    {
        return $this->getInstance()->__invoke(...$args);
    }


    public function getInstance()
    {
        if ($this->instance == null) {
            $provider = $this->provider;
            $this->instance = $provider($this->injector);
        }
        return $this->instance;
    }

}