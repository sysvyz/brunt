<?php
/**
 * Created by PhpStorm.
 * User: mb
 * Date: 08.05.16
 * Time: 23:34
 */

namespace Brunt\Provider;


use Brunt\Injector;
use Brunt\Provider\Lazy\LazyProxyBuilder;
use \Brunt\Provider\I\ClassProvider as ClassProviderInterface;

class LazyClassProvider extends LazyProvider implements  ClassProviderInterface
{
    /**
     * @var ClassProvider
     */
    protected $provider;
    
    /**
     * SingletonProvider constructor.
     * @param Provider $provider
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
    function __invoke(Injector $injector)
    {

        /** @var LazyProxyBuilder $builder */
        $builder = $injector->{LazyProxyBuilder::class};


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
        $this->provider->getClass();
    }

    public function getReflector()
    {
        $this->provider->getReflector();
    }
}