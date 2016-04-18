<?php
/**
 * Created by PhpStorm.
 * User: mb
 * Date: 17.04.16
 * Time: 14:37
 */

namespace SVZ;


class Reflector extends Injectable
{

    private $reflectionClass;
    private $className;

    /**
     * DIReflector constructor.
     * @param string $className
     * @param Injector $inj
     */

    public function __construct(string $className)
    {
        $this->reflectionClass = new \ReflectionClass($className);
        $this->className = $className;
    }

    public function isInjectable()
    {
        return $this->reflectionClass->implementsInterface(InjectableInterface::class);
    }

    public function getReference()
    {
        return [
            'class' => $this->reflectionClass->getName(),
            'interfaces' => $this->reflectionClass->getInterfaceNames(),
            'ext' => $this->reflectionClass->getExtensionName(),
            'dependencies' => $this->getDependencies(),
            'constructor' => $this->reflectionClass->getConstructor(),
            'isInjectable' => $this->isInjectable(),

        ];
    }

    public function getDependencies()
    {
        $class = $this->className;
        $constructor = $this->reflectionClass->getConstructor();
        $parameters = ($constructor) ? $constructor->getParameters() : [];
        $ret = array_map(
            function (\ReflectionParameter $e) use ($class) {
                $type = $e->getType();
                $isNative = $type->isBuiltin();
                $name = $e->getName();
                $dependencies = $class::_DI_DEPENDENCIES();
                return [
                    'class' => $e->getClass(),
                    'type' => $type,
                    'typeString' => $type . '',
                    'isNative' => $isNative,
                    'name' => $name,
                    'position' => $e->getPosition(),
                    'native' =>
                        $isNative
                        && isset($dependencies[$name])
                            ? $dependencies[$name]
                            : NULL,
                ];
            }, $parameters

        );

        return $ret;
    }

    /**
     * @return string
     */
    public function getClassName()
    {
        return $this->className;
    }

    /**
     * @return \ReflectionClass
     */
    public function getReflectionClass()
    {
        return $this->reflectionClass;
    }
}