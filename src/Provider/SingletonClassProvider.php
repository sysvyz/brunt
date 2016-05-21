<?php
/**
 * Created by PhpStorm.
 * User: mb
 * Date: 08.05.16
 * Time: 23:34
 */

namespace Brunt\Provider;


use Brunt\Provider\I\ClassProvider as ClassProviderInterface;
use Reflector;

class SingletonClassProvider extends SingletonProvider implements ClassProviderInterface
{
    /**
     * @var ClassProvider
     */
    protected $provider;

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