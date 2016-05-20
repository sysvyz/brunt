<?php
/**
 * Created by PhpStorm.
 * User: mb
 * Date: 17.04.16
 * Time: 14:37
 */

namespace Brunt\Reflection {

    use Brunt\Exception\InjectableException;
    use Brunt\Reflection\CR\CRClass;
    use Brunt\Reflection\CR\CRMethod;
    use Brunt\Reflection\CR\CRParam;


    class Reflector
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

        /**
         * @return bool
         */
        public function hasDependencies()
        {
            return $this->reflectionClass->hasMethod('_DI_DEPENDENCIES');
        }

        /**
         * @return array
         */
        public function getDependencies()
        {
            if ($this->hasDependencies()) {
                $className = $this->className;
                return $className::_DI_DEPENDENCIES();
            }
            return [];
        }

        /**
         * @return bool
         */
        public function hasProviders()
        {
            return $this->reflectionClass->hasMethod('_DI_PROVIDERS');
        }


        /**
         * @return array
         */
        public function getProviders()
        {
            if ($this->hasProviders()) {
                $className = $this->className;
                return $className::_DI_PROVIDERS();
            }
            return [];
        }

        /**
         *  PHP7 magic
         */
        public function resolveDependencies()
        {
            $dependencies = $this->getDependencies();

            return array_map(function (\ReflectionParameter $param) use ($dependencies) {
                $paramName = $param->name;

                $type = $param->getType();

                $token = '';
                $native = !$type || $type->isBuiltin();
                if ($native) {
                    if (isset($dependencies[$paramName])) {
                        $token = $dependencies[$paramName];
                    } else {
                        $this->_throwNativeParamNotFound($paramName);
                    }
                } else {
                    $token = $type . '';
                }

                return ['param' => $paramName, 'token' => $token, 'isNative' => $native];

            }, $this->getConstructorParams());
        }

        private function _throwNativeParamNotFound($paramName)
        {
            throw new InjectableException("native param '$paramName' of '$this->className' not found");
        }

        /**
         * @return \ReflectionParameter[]
         */
        public function getConstructorParams()
        {
            $constructor = $this->reflectionClass->getConstructor();
            return $constructor ? $constructor->getParameters() : [];
        }

        public function getCompactReferenceClass()
        {
            
            $methods = $this->reflectionClass->getMethods();
            $ms = [];
            foreach ($methods as $method) {
                $params = $method->getParameters();
                $ps = [];
                foreach ($params as $param) {
                    $ps  [] = new CRParam($param->getName() . "", $param);
                }
                $ms [] = new CRMethod($method, $ps);
            }

            $fields = $this->reflectionClass->getProperties();

            return new CRClass($this->reflectionClass, $ms);

        }
    }


}