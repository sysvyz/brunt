<?php
/**
 * Created by PhpStorm.
 * User: mb
 * Date: 08.05.16
 * Time: 23:34
 */

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
     *
     * convenience function wrapper for constructor
     *
     * @param $class
     * @param bool $singleton
     * @return SingletonProvider
     */
    public static function init($class, $singleton = true)
    {

        return new self($class, $singleton);
    }

    /**
     * SingletonProvider constructor.
     * @param Provider $provider
     */
    public function __construct(Provider $provider)
    {
        $this->provider = $provider;
    }


    /**
     * @param Injector $injector
     * @return mixed
     */
    function __invoke(Injector $injector)
    {
        return new LazyProxyObject($this->provider,$injector);
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