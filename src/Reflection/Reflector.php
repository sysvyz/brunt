<?php

namespace Brunt\Reflection {

    use Brunt\Exception\InjectableException;
    use Brunt\Reflection\CR\CRClass;
    use Brunt\Reflection\CR\CRField;
    use Brunt\Reflection\CR\CRMethod;
    use Brunt\Reflection\CR\CRParam;
    use Brunt\Reflection\CR\Reflective\ReflectiveCRClass;
    use Brunt\Reflection\CR\Reflective\ReflectiveCRField;
    use Brunt\Reflection\CR\Reflective\ReflectiveCRMethod;
    use Brunt\Reflection\CR\Reflective\ReflectiveCRParam;


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
            
//            if (is_string($class)) {
//                $this->reflectionClass = new \ReflectionClass($class);
//
//            } else  {
//               print_r('ERROR');die;
//
//            }
            $this->cRClass = $class;
        //    $this->_getCompactReferenceClass();
        }
//
//        /**
//         * @return CRClass
//         */
//        private function _getCompactReferenceClass()
//        {
//
//            $methods = $this->reflectionClass->getMethods();
//            $ms = [];
//            foreach ($methods as $method) {
//                $params = $method->getParameters();
//                $ps = [];
//                foreach ($params as $param) {
//                    $ps  [$param->getName()] = new ReflectiveCRParam($param->getName() . "", $param);
//                }
//                $ms [$method->getName()] = new ReflectiveCRMethod($method, $ps);
//            }
//            $fields = $this->reflectionClass->getProperties();
//            $fs = [];
//            foreach ($fields as $field) {
//                $fs[$field->getName()] = new ReflectiveCRField($field);
//
//            }
//
//            return new ReflectiveCRClass($this->reflectionClass, $ms, $fs);
//
//        }

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
         * @return \ReflectionParameter[]
         */
        public function getConstructorParams()
        {
            /** @var CRMethod $c */
            $c = $this->cRClass->getConstructor();


            return $c->getParams();


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