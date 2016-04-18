<?php
namespace SVZ;

/**
 * Created by PhpStorm.
 * User: mb
 * Date: 17.04.16
 * Time: 01:25
 */
class Provider implements ProviderInterface
{

    private $callable = [];

    /**
     * DIProvider constructor.
     * @param $callable
     */
    public function __construct(callable $callable)
    {
        $this->callable[0] = $callable;
    }

    function __invoke(Injector $injector)
    {
        return $this->callable[0]($injector);
    }

    public static function init(callable $callable)
    {
        return new static($callable);
    }

}