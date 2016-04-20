<?php
/**
 * Created by PhpStorm.
 * User: mb
 * Date: 20.04.16
 * Time: 20:22
 */

namespace Prescription\Provider {



    class Binding
    {
        /**
         * @var string
         */
        private $token;

        /**
         * @var Provider
         */
        private $provider;

        /**
         * Binding constructor.
         * @param string $token
         */
        public function __construct(string $token)
        {
            $this->token = $token;
        }

        public static function init($class)
        {
            return new self($class);
        }

        public function toClass(string $className,bool $singleton = true)
        {
            $this->provider = new ClassProvider($className,$singleton);
            return $this;
        }

        public function toValue($value)
        {
            $this->provider = new ValueProvider($value);
            return $this;
        }

        public function toFactory(callable $callable)
        {
            $this->provider = new FactoryProvider($callable);
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
            return $this->provider;
        }

        public static function __callStatic($name, $arguments)
        {
            // TODO: Implement __callStatic() method.
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