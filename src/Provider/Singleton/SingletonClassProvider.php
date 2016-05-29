<?php


namespace Brunt\Provider\Singleton;


use Brunt\Injector;
use Brunt\Provider\Classes\ClassProvider;
use Brunt\Provider\I\ClassProviderInterface;
use Brunt\Provider\Lazy\LazyClassProvider;
use Reflector;

class SingletonClassProvider extends SingletonProvider implements ClassProviderInterface
{
    /**
     * @var ClassProviderInterface
     */
    protected $provider;
    /**
     * SingletonProvider constructor.
     * @param ClassProviderInterface $provider
     */
    public function __construct(ClassProviderInterface $provider)
    {
        parent::__construct($provider);
        $this->provider = $provider;
    }

    /**
     * @return Reflector
     */
    public function getReflector()
    {
        return $this->provider->getReflector();
    }
    /**
     * @return SingletonProvider
     */

    public function getClass()
    {
        return $this->provider->getReflector();
    }


    public function lazy(){
        return new LazyClassProvider($this);
    }

  

}