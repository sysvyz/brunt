<?php

namespace Brunt\Reflection {

    use Brunt\Exception\InjectableException;
    use Brunt\Reflection\CR\CRClass;
    use Brunt\Reflection\CR\CRParam;


    class Reflector
    {

        private $reflectionClass;
        /**
         * @var CRClass
         */
        private $cRClass;

        /**
         * Reflector constructor.
         * @param $class
         */
        public function __construct(CRClass $class)
        {

            $this->cRClass = $class;

        }


        /**
         * @return \ReflectionProperty[]
         */
        public function getProperties()
        {

            return $this->cRClass->getFields();
        }

        /**
         * @deprecated
         * @return \ReflectionClass
         */
        public function getReflectionClass()
        {
            return $this->reflectionClass;
        }

        /**
         * @return \ReflectionClass
         */
        public function getFileName()
        {

            return $this->cRClass->getFileName();
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
            return $this->cRClass->hasProviders();
        }

        /**
         * @return string
         */
        public function getClassName()
        {
            return $this->cRClass->getClassName();
        }

        /**
         *  PHP7 magic
         */
        public function resolveDependencies($params, $prefix = '')
        {
            $dependencies = $this->getDependencies();

            return array_map(function (CRParam $param) use ($dependencies, $prefix) {
                $paramName = ($prefix ? $prefix . '.' : '') . $param->getName();
                $type = $param->getType();
                $native = !$param->hasType() || $param->isBuiltin();
                if ($native) {
                    if (isset($dependencies[$paramName])) {
                        $token = $dependencies[$paramName];
                    } else {
                        $this->_throwNativeParamNotFound($paramName);
                        die;
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
            return $this->cRClass->hasDependencies();
        }

        private function _throwNativeParamNotFound($paramName)
        {
            throw new InjectableException("native param '$paramName' of '" . $this->getClassName() . "' not found");
        }

        /**
         * @return CRParam[]
         */
        public function getConstructorParams()
        {
            return $this->cRClass->getConstructor()->getParams();
        }

        public function getCompactReferenceClass()
        {
            return $this->cRClass;
        }

        public function toArray()
        {
            return [
                'getCompactReferenceClass' => $this->getCompactReferenceClass()->toArray(),
                'hasDependencies' => $this->hasDependencies(),
                'getDependencies' => $this->getDependencies(),
                'getFileName' => $this->getFileName(),
                'hasProviders' => $this->hasProviders(),
                'getClassName' => $this->getClassName(),

            ];

        }

    }

}