<?php

namespace Brunt\Provider\Lazy;


use Brunt\Injector;
use Brunt\Provider\Classes\ClassProvider;
use Brunt\Provider\I\ClassProviderInterface;
use Brunt\Provider\I\ProviderInterface;
use Brunt\Provider\Singleton\SingletonProvider;

class LazyClassProvider extends LazyProvider implements ClassProviderInterface
{
    /**
     * @var ClassProvider
     */
    protected $provider;

    /**
     * SingletonProvider constructor.
     * @param ProviderInterface $provider
     */
    public function __construct(ClassProviderInterface $provider)
    {
        $this->provider = $provider;
        parent::__construct($provider);
    }
    
    /**
     * @param Injector $injector
     * @return mixed
     */
    function get(Injector $injector)
    {
        /** @var LazyProxyBuilder $builder */
        $builder = LazyProxyBuilder::init();
        return $builder->create($injector, $this->provider);
    }

    /**
     * @return SingletonProvider
     */
    public function singleton()
    {
        $this->provider = $this->provider->singleton();
        return $this;
    }

    public function getClass()
    {
        return $this->provider->getClass();
    }

    public function getReflector()
    {
        return $this->provider->getReflector();
    }

    public function lazy()
    {
        return $this;
    }
}