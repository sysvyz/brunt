<?php

namespace Brunt {

    use Brunt\Exception\ProviderNotFoundException;
    use Brunt\Provider\AliasProvider;
    use Brunt\Provider\Classes\ClassFactoryProvider;
    use Brunt\Provider\Classes\ClassProvider;
    use Brunt\Provider\AbstractProvider;
    use Brunt\Provider\I\ProviderInterface;
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
         * @var AbstractProvider
         */
        private $provider;
        /**
         * @var bool
         */
        private $isSingleton = false;
        /**
         * @var bool
         */
        private $isLazy = false;
        /**
         * @var bool
         */
        private $isTokenIsClass = false;
        /**
         * @var bool
         */
        private $isValue = false;
        /**
         * @var bool
         */
        private $isAlias = false;

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
         * @param $name
         * @param $arguments
         * @return Binding
         */
        public static function __callStatic($name, $arguments)
        {
            return Binding::init($name);
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
        public function toValue($value)
        {
            $this->isValue = true;
            $this->provider = new ValueProvider($value);
            return $this;
        }

        /**
         * @return Binding
         */
        public function toAlias($alias)
        {
            $this->isAlias = true;
            $this->provider = new AliasProvider($alias);
            return $this;
        }

        /**
         * @return Binding
         */
        public function toFactory(callable $callable)
        {
            if ($this->isTokenIsClass) {
                $this->provider = new ClassFactoryProvider($this->token, $callable);
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
         * @return ProviderInterface
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
         * @param $implementation
         * @return Binding
         */
        public function toClass($implementation)
        {
            if (is_callable($implementation)) {
                $this->provider = new ClassFactoryProvider($this->token, $implementation);
            } else {
                $this->provider = new ClassProvider($implementation);
            }


            return $this;
        }


    }




}
