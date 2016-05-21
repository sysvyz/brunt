<?php

namespace Brunt {

    use Brunt\Exception\ProviderNotFoundException;
    use Brunt\Provider\ClassFactoryProvider;
    use Brunt\Provider\ClassProvider;
    use Brunt\Provider\ConcreteProvider;
    use Brunt\Provider\FactoryProvider;
    use Brunt\Provider\Provider;
    use Brunt\Provider\ValueFactoryProvider;
    use Brunt\Provider\ValueProvider;


    /**
     * Class Binding
     * @package Brunt\Provider
     *
     */
    class Binding
    {
        /**
         * @var string
         */
        private $token;

        /**
         * @var ConcreteProvider
         */
        private $provider;
        private $isSingleton = false;
        private $isLazy = false;

        private $isTokenIsClass = false;

        /**
         * Binding constructor.
         * @param string $token
         */
        public function __construct(string $token)
        {
            $this->token = $token;

            $this->isTokenIsClass = class_exists($token);
        }

        /**
         * @param $token
         * @return Binding
         */
        public static function init($token)
        {
            return new self($token);
        }

        /**
         * @return Binding
         */
        public function toClass(string $className)
        {
            $this->provider = new ClassProvider($className);
            return $this;
        }

        /**
         * @return Binding
         */
        public function toValue($value)
        {
            $this->provider = new ValueProvider($value);
            return $this;
        }

        /**
         * @return Binding
         */
        public function toFactory(callable $callable)
        {
            if ($this->isTokenIsClass) {
                $this->provider = new ClassFactoryProvider($this->token,$callable);
            } else {
                $this->provider = new ValueFactoryProvider($callable);
            }
            return $this;
        }

        /**
         * @return Binding
         */
        public function singleton()
        {
            $this->isSingleton = true;
            return $this;
        }

        /**
         * @return Binding
         */
        public function lazy()
        {
            $this->isLazy = true;

            return $this;
        }

        /**
         * @return string
         */
        public function getToken()
        {
            return $this->token;
        }


        /**
         * @return Provider
         */
        public function getProvider()
        {
            if ($this->provider == null && $this->isTokenIsClass) {
                $this->toClass($this->token);
            }
            if ($this->provider == null) {
                throw new ProviderNotFoundException($this->token . ' is no class');
            }
            if ($this->isSingleton) {
                $this->provider = $this->provider->singleton();
            }
            if ($this->isLazy) {
                $this->provider = $this->provider->lazy();
            }


            return $this->provider;
        }


        /**
         * @param $name
         * @param $arguments
         * @return Binding
         */
        public static function __callStatic($name, $arguments)
        {
            return Binding::init($name);
        }


    }


    /**
     * convenience function for bindings
     * @param string $token
     * @return Binding
     */
    function bind(string $token)
    {
        return new Binding($token);
    }

}