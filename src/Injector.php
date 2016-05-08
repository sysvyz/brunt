<?php
/**
 * Created by PhpStorm.
 * User: mb
 * Date: 16.04.16
 * Time: 21:46
 */

namespace Brunt {

    use Brunt\Exception\ProviderNotFoundException;
    use Brunt\Provider\ClassProvider;

    class Injector
    {

        private $token = null;
        private $injector;
        private $providers = [];

        /**
         * Injector constructor.
         * @param $injector
         */
        public function __construct(Injector $injector = null)
        {
            $this->injector = $injector;
        }

        /**
         * @return Injector parent ionjector
         */
        public function getParent()
        {
            return $this->injector;
        }

        /**
         * @param string $token
         * @return $this|mixed instance
         */
        public function get(string $token)
        {
            if ($token == self::class) {
                return $this;
            }

            return $this->_get($token);
        }

        /**
         * the real function
         *
         * @param string $token
         * @return mixed instance
         */
        private function _get(string $token)
        {
            //if provider exists this injector is responsible
            if (isset($this->providers[$token])) {
                //execute provider
                $instance = $this->providers[$token]($this);
            } else if ($this->injector) {
                //if parent injector exists
                //recursive search in parent injector
                $instance = $this->injector->_get($token);
            } else {
                //until root injector has no provider
                throw new ProviderNotFoundException($token . '...provider not found');
            }

            return $instance;
        }

        function __get($name)
        {
            return $this->get($name);
        }


        public function provide(string $token, callable $callable)
        {
            $this->providers[$token] = $callable;
        }

        public function providers(array $providers = [])
        {
            foreach ($providers as $name => $provider) {
                if (is_int($name) && is_string($provider)) {
                    $this->provide($provider, ClassProvider::init($provider));
                } else if (is_callable($provider)) {
                    $this->provide($name, $provider);
                } else if (is_string($provider)) {
                    $this->provide($name, ClassProvider::init($provider));
                }
            }
        }

        public function bind($bindings)
        {
            if (is_array($bindings)) {
                array_walk($bindings, [$this, 'bind']);
            } else if ($bindings instanceof Binding) {
                $this->providers[$bindings->getToken()] = $bindings->getProvider();
            }
        }

        public function getChild($providers = [])
        {
            $child = new self($this);
            $child->providers($providers);
            return $child;
        }

    }
}