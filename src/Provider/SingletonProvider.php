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
    private $provider;

    private $instance;

    /**
     *
     * convenience function wrapper for constructor
     *
     * @param $class
     * @param bool $singleton
     * @return ClassProvider
     */
    public static function init($class, $singleton = true)
    {

        return new self($class, $singleton);
    }
    /**
     * SingletonProvider constructor.
     */
    public function __construct(Provider $provider)
    {
        $this->instance = null;
        $this->provider = $provider;
    }



    function __invoke(Injector $injector)
    {

        print_r('get');
        if ($this->instance === null) {
            print_r('construct');
            $p = $this->provider;
            $this->instance = $p($injector);
        }
        return $this->instance;

    }
}