<?php

namespace Brunt\Provider;

use Brunt\Injector;
use Brunt\Provider\Lazy\LazyProxyObject;

class LazyProvider implements Provider
{
    /**
     * @var Provider
     */
    private $provider;

    /**
     * SingletonProvider constructor.
     * @param Provider $provider
     */
    public function __construct(Provider $provider)
    {
        $this->provider = $provider;
    }

    /**
     * convenience function wrapper for constructor
     *
     * @param $class
     * @return LazyProvider
     */
    public static function init($class):LazyProvider
    {
        return new self($class);
    }

    /**
     * @param Injector $injector
     * @return mixed
     */
    function __invoke(Injector $injector):LazyProxyObject
    {
        return new LazyProxyObject($this->provider, $injector);
    }

    /**
     * @return SingletonProvider
     */
    public function singleton()
    {
        $this->provider = $this->provider->singleton();
        return $this;
    }

    public function lazy()
    {
        return $this;
    }
}