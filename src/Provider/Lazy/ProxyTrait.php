<?php
/**
 * Created by PhpStorm.
 * User: mb
 * Date: 20.05.16
 * Time: 03:23
 */

namespace Brunt\Provider\Lazy;


use Brunt\Injector;
use Brunt\Provider\Provider;

trait ProxyTrait
{
    /**
     * @var Provider
     */
    private $provider;
    /**
     * @var mixed
     */
    private $instance_eae9cc3e;
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

        $r = new \ReflectionClass($this);
        $reserved = ['provider','injector','instance_eae9cc3e'];

        foreach ($r->getProperties() as $item) {
            $name = $item->getName();
            if(!in_array($name, $reserved)){
                unset($this->{$name});
            }
        }

        $this->provider = $provider;
        $this->injector = $injector;
        $this->instance_eae9cc3e = null;

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
        if($this->init){
            $this->getInstance()->$name = $value;
        }else{
            $this->$name = $value;
        }
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
        if ($this->instance_eae9cc3e == null) {
            $provider = $this->provider;
            $this->instance_eae9cc3e = $provider($this->injector);
        }

        return $this->instance_eae9cc3e;
    }


}