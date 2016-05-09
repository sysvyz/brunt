<?php
/**
 * Created by PhpStorm.
 * User: mb
 * Date: 20.04.16
 * Time: 20:22
 */

namespace Brunt {

    use Brunt\Provider\ClassProvider;
    use Brunt\Provider\ConcreteProvider;
    use Brunt\Provider\FactoryProvider;
    use Brunt\Provider\Provider;
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

        /**
         * Binding constructor.
         * @param string $token
         */
        public function __construct(string $token)
        {
            $this->token = $token;
        }

        /**
         * @param $token
         * @return Binding
         */
        public static function init($token)
        {
            return new self($token);
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
         * @return Provider
         */
        public function singleton()
        {
            $this->provider = $this->provider->singleton();
            return $this;
        }        /**
     * @return Provider
     */
        public function lazy()
        {
            $this->provider = $this->provider->lazy();
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