<?php

namespace Brunt\Reflection {

    use Brunt\Exception\InjectableException;
    use Brunt\Reflection\CR\CRClass;
    use Brunt\Reflection\CR\CRMethod;
    use Brunt\Reflection\CR\CRParam;


    class Reflector
    {

        private $reflectionClass;


        /**
         * DIReflector constructor.
         * @param string $className
         * @param Injector $inj
         */

        public function __construct($class)
        {
            if (is_string($class)) {
                $this->reflectionClass = new \ReflectionClass($class);

            } else if ($class instanceof \ReflectionClass) {
                $this->reflectionClass = $class;

            }
        }

        /**
         * @return string
         */
        public function getClassName()
        {
            return $this->reflectionClass->getName();
        }

        /**
         * @return \ReflectionClass
         */
        public function getReflectionClass()
        {
            return $this->reflectionClass;
        }

        /**
         * @return array
         */
        public function getProviders()
        {
            if ($this->hasProviders()) {
                $className = $this->getClassName();
                return $className::_DI_PROVIDERS();
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
         *  PHP7 magic
         */
        public function resolveDependencies($params,$prefix = '')
        {
            $dependencies = $this->getDependencies();

            return array_map(function (\ReflectionParameter $param) use ($dependencies,$prefix) {
                $paramName = ($prefix?$prefix.'.':'').$param->name;

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

            }, $params);
        }

        /**
         * @return array
         */
        public function getDependencies()
        {
            if ($this->hasDependencies()) {
                $className = $this->getClassName();
                return $className::_DI_DEPENDENCIES();
            }
            return [];
        }

        /**
         * @return bool
         */
        public function hasDependencies()
        {
            return $this->reflectionClass->hasMethod('_DI_DEPENDENCIES');
        }

        private function _throwNativeParamNotFound($paramName)
        {
            throw new InjectableException("native param '$paramName' of '".$this->getClassName()."' not found");
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