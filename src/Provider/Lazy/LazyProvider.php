<?php

namespace Brunt\Provider\Lazy;

use Brunt\Injector;
use Brunt\Provider\Contract\ProviderInterface;
use Brunt\Provider\Lazy\LazyProxyObject;
use Brunt\Provider\SingletonProvider;

class LazyProvider implements ProviderInterface
{
    /**
     * @var ProviderInterface
     */
    private $provider;

    /**
     * SingletonProvider constructor.
     * @param ProviderInterface $provider
     */
    public function __construct(ProviderInterface $provider)
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
    function get(Injector $injector):LazyProxyObject
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
//    function __invoke(Injector $injector)
//    {
//        return $this->get($injector);
//    }
}