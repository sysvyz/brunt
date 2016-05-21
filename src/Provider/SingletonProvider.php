<?php
/**
 * Created by PhpStorm.
 * User: mb
 * Date: 08.05.16
 * Time: 23:34
 */

namespace Brunt\Provider;


use Brunt\Injector;

class SingletonProvider implements Provider
{
    /**
     * @var Provider
     */
    protected $provider;

    protected $instance;

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
        $this->instance = null;
        $this->provider = $provider;
    }


    /**
     * @param Injector $injector
     * @return mixed
     */
    function __invoke(Injector $injector)
    {

        if ($this->instance === null) {
            $p = $this->provider;
            $this->instance = $p($injector);
        }
        return $this->instance;

    }

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