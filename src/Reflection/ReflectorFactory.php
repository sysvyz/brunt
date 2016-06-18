<?php
/**
 * Created by PhpStorm.
 * User: mb
 * Date: 13.06.16
 * Time: 00:13
 */

namespace Brunt\Reflection;


use Brunt\Cache\ReflectionCache;
use Brunt\Reflection\CR\Cache\CacheCRClass;
use Brunt\Reflection\CR\Reflective\ReflectiveCRClass;
use Brunt\Reflection\CR\Reflective\ReflectiveCRField;
use Brunt\Reflection\CR\Reflective\ReflectiveCRMethod;
use Brunt\Reflection\CR\Reflective\ReflectiveCRParam;

class ReflectorFactory
{

    private static $reflectors = [];

    public static function buildReflectorByClassName($className)
    {
        $reflectionCache = ReflectionCache::init();

        if (isset(self::$reflectors[$className])) {
            $reflector = self::$reflectors[$className];
        } else if ($reflectionCache->isCached($className)) {

            $data = $reflectionCache->read($className);

            $reflector = new Reflector(self::buildCacheCRClass($data));
            self::$reflectors[$className] = $reflector;

        } else {
            $reflector = new Reflector(self::buildReflectiveCRClass($className));
            $reflectionCache->write($reflector);
           // self::$reflectors[$className] = $reflector;
        }


        return $reflector;
    }

    private static function buildCacheCRClass($data)
    {

        return new CacheCRClass($data['getCompactReferenceClass']);


        // return self::buildReflectiveCRClass($className);
    }

    private static function buildReflectiveCRClass($className)
    {
        $reflectionClass = self::buildReflectionClass($className);
        $methods = $reflectionClass->getMethods();
        $ms = [];
        foreach ($methods as $method) {
            $params = $method->getParameters();
            $ps = [];
            foreach ($params as $param) {
                $ps  [$param->getName()] = new ReflectiveCRParam($param->getName() . "", $param);
            }
            $ms [$method->getName()] = new ReflectiveCRMethod($method, $ps);
        }
        $fields = $reflectionClass->getProperties();
        $fs = [];
        foreach ($fields as $field) {
            $fs[$field->getName()] = new ReflectiveCRField($field);

        }

        $crc = new ReflectiveCRClass($reflectionClass, $ms, $fs);
        //print_r($crc);

        return $crc;
    }

    private static function buildReflectionClass(string $class)
    {

        return new \ReflectionClass($class);


    }
}