<?php


namespace Brunt\Provider\Lazy;


use Brunt\Exception\ProxyBuildErrorException;
use Brunt\Injector;
use Brunt\Provider\I\ClassProviderInterface;

class LazyProxyBuilder
{


    static $classNames = [];

    /**
     * ProxyBuilder constructor.
     */
    public function __construct()
    {
    }

    public function create(Injector $injector, ClassProviderInterface $provider)
    {


        $proxy = null;

        $reflector = $provider->getReflector();
        $className = $reflector->getClassName();

        if (isset(self::$classNames[$className])) {
            $proxyClassName = self::$classNames[$className];
            return new $proxyClassName($provider, $injector);
        }

        $proxyClassName = $this->proxifyClassName($className);
        $renderer = new ProxyRenderer($reflector, $proxyClassName);

        eval($renderer . '$proxy = new ' . $proxyClassName . '($provider, $injector);');

        if ($proxy == null) {
            throw new ProxyBuildErrorException(
                "something went horribly wrong:
please inform the maintainer of brunt!
this should never happen, therefore it is a major issue"
            );
        }
        self::$classNames[$className] = $proxyClassName;
        return $proxy;

    }

    /**
     * @param string $getClassName
     * @return string
     */
    public function proxifyClassName(string $getClassName)
    {

        return $this->sanitizeClassName($getClassName) . '_Brunt_Proxy';
    }

    /**
     * @param string $getClassName
     * @return mixed
     */
    private function sanitizeClassName(string $getClassName)
    {
        return str_replace('\\', '_', $getClassName);
    }

}