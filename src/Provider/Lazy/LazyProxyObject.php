<?php


namespace Brunt\Provider\Lazy;


use Brunt\Injector;
use Brunt\Provider\Provider;
use Traversable;

class AAA{
    private $a = 1;
    protected $b = 2;
    public $c = 3;
}

class LazyProxyObject extends AAA implements \ArrayAccess , \IteratorAggregate
{
    use ProxyTrait;



}
