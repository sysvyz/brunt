<?php


namespace Brunt\Provider\Lazy;


use Brunt\Cache\ProxyCache;
use Brunt\Exception\ProxyBuildErrorException;
use Brunt\Injector;
use Brunt\Provider\Contract\ClassProviderInterface;

class LazyProxyBuilder
{

    public static $t1 = 0;
    public static $t2= 0;
    public static $t2_= 0;
    public static $t3 = 0;
    private static $instance = null;

    protected static $classNames = [];
    private $cache;


    public static function init()
    {
        if(!self::$instance){
            self::$instance=new self(ProxyCache::init());
        }

        return self::$instance;
    }
    
    /**
     * ProxyBuilder constructor.
     */
    private function __construct(ProxyCache $proxyCache)
    {
        $this->cache = $proxyCache;
    }

    public function create(Injector $injector, ClassProviderInterface $provider)
    {

        $t1 = microtime(true);
        $proxy = null;

        $reflector = $provider->getReflector();
        $className = $reflector->getClassName();

        $proxyClassName = $this->getProxyClassName($className);

        if (isset( self::$classNames[$className])) {

            $proxyNSClassName = 'Brunt\ProxyObject\\' . $proxyClassName;
            $proxy = new  $proxyNSClassName($provider, $injector);
            $t2 = microtime(true);
            self::$t1 += $t2-$t1;

        } else if ($this->cache->read($reflector, $proxyClassName)) {
            self::$t2_ += microtime(true)-$t1;

            $proxyNSClassName = 'Brunt\ProxyObject\\' . $proxyClassName;
            $proxy = new  $proxyNSClassName($provider, $injector);

            $t2 = microtime(true);
            self::$t2 += $t2-$t1;
        } else {


            $renderer = new ProxyRenderer($reflector->getCompactReferenceClass(), $proxyClassName);

            $class = $renderer->__toString();

            eval($class);

            $proxyNSClassName = 'Brunt\ProxyObject\\' . $proxyClassName;
            $proxy = new $proxyNSClassName($provider, $injector);


            $this->cache->write($class, $reflector, $proxyClassName);
            $t2 = microtime(true);
            self::$t3 += $t2-$t1;
        }


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
     * @param $className
     * @return mixed|string
     */
    public function getProxyClassName($className)
    {
        if (isset(self::$classNames[$className])) {
            $proxyClassName = self::$classNames[$className];
            return $proxyClassName;
        } else {
            $proxyClassName = $this->proxifyClassName($className);
            return $proxyClassName;
        }
    }

    /**
     * @param string $getClassName
     * @return string
     */
    private function proxifyClassName(string $getClassName)
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