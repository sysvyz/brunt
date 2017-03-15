<?php

namespace Brunt\Provider\Singleton;


use Brunt\Injector;
use Brunt\Provider\Contract\ProviderInterface;
use Brunt\Provider\Lazy\LazyProvider;

class SingletonProvider implements ProviderInterface
{
    /**
     * @var ProviderInterface
     */
    protected $provider;

    protected $instance;



    /**
     * SingletonProvider constructor.
     * @param ProviderInterface $provider
     */
    public function __construct(ProviderInterface $provider)
    {
        $this->instance = null;
        $this->provider = $provider;
    }


    /**
     * @param Injector $injector
     * @return mixed
     */
    function get(Injector $injector)
    {
     
        if ($this->instance === null) {
            $p = $this->provider;
            $this->instance = $p->get($injector);
        }
        return $this->instance;

    }

//    function __invoke(Injector $injector)
//    {
//        return $this->get($injector);
//    }
    /**
     * @return SingletonProvider
     */
    public function singleton()
    {
        return $this;
    }
    public function lazy(){
        return new LazyProvider($this);
    }
}