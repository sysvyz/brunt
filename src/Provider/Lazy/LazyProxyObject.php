<?php
/**
 * Created by PhpStorm.
 * User: mb
 * Date: 09.05.16
 * Time: 02:16
 */

namespace Brunt\Provider\Lazy;


use Brunt\Injector;
use Brunt\Provider\Provider;
class AAA{
    private $a = 1;
    protected $b = 2;
    public $c = 3;
}

class LazyProxyObject extends AAA
{
    use ProxyTrait;

}
