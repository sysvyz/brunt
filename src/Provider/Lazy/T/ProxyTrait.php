<?php


namespace Brunt\Provider\Lazy\T;

use Brunt\Injector;
use Brunt\Provider\I\ClassProvider;
use Brunt\Provider\Provider;


trait ProxyTrait
{
    /**
     * @var Provider
     */
    private $provider_9a5f1a83;
    /**
     * @var mixed
     */
    private $instance_eae9cc3e;
    /**
     * @var Injector
     */
    private $injector_b291a118;


    /**
     * LazyProxyTrait constructor.
     * @param Provider $provider
     * @param Injector $injector
     */
    public function __construct(Provider $provider, Injector $injector)
    {
        if ($provider instanceof ClassProvider) {
            $reflectionClass = $provider->getReflector()->getReflectionClass();
            foreach ($reflectionClass->getProperties() as $item) {
                if (!$item->isPrivate() && !$item->isStatic()) {
                    unset($this->{$item->getName()});
                }
            }
        } else {
            $reflectionClass = new \ReflectionClass($this);
            $reserved = ['provider_9a5f1a83', 'injector_b291a118', 'instance_eae9cc3e'];
            foreach ($reflectionClass->getProperties() as $item) {
                $name = $item->getName();
                if (!in_array($name, $reserved) && !$item->isStatic() && !$item->isPrivate()) {

                    unset($this->{$name});
                }
            }
        }

        $this->provider_9a5f1a83 = $provider;
        $this->injector_b291a118 = $injector;
        $this->instance_eae9cc3e = null;

    }

    public function __call($name, $arguments)
    {
        return $this->getInstance()->$name(...$arguments);
    }

    public function getInstance()
    {
        if ($this->instance_eae9cc3e == null) {
            $provider = $this->provider_9a5f1a83;
            $this->instance_eae9cc3e = $provider($this->injector_b291a118);
        }
        return $this->instance_eae9cc3e;
    }

    public function __get($name)
    {
        return $this->getInstance()->$name;
    }

    public function __set($name, $value)
    {
        return $this->getInstance()->$name = $value;
    }

    public function __toString()
    {
        return $this->getInstance() . '';
    }

    function __isset($name)
    {
        return isset($this->getInstance()->$name);
    }

    function __unset($name)
    {
        unset($this->getInstance()->$name);
    }

    function __invoke(...$args)
    {
        $i = $this->getInstance();
        return $i(...$args);
    }


}