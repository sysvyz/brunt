<?php

namespace Brunt {

    use Brunt\Exception\ProviderNotFoundException;
    use Brunt\Provider\ClassProvider;
    use Brunt\Provider\Lazy\LazyProxyBuilder;

    class Injector
    {

        private $injector;
        private $providers = [];

        /**
         * Injector constructor.
         * @param $injector
         */
        public function __construct(Injector $injector = null)
        {
            $this->injector = $injector;

            $this->providers([LazyProxyBuilder::class => ClassProvider::init(LazyProxyBuilder::class)->singleton()]);
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

        public function provide(string $token, callable $callable)
        {
            $this->providers[$token] = $callable;
        }

        /**
         * @return Injector parent ionjector
         */
        public function getParent()
        {
            return $this->injector;
        }

        function __get($name)
        {
            return $this->get($name);
        }

        /**
         * @param string $token
         * @return mixed instance
         */
        public function get(string $token)
        {
            if ($token == self::class) {
                return $this;
            }

            $provider = $this->getProvider($token);

            return $provider($this);
        }

        /**
         * @param string $token
         * @return mixed instance
         */
        public function getProvider(string $token)
        {
            //if provider exists this injector is responsible
            if (isset($this->providers[$token])) {
                //execute provider
                $provider = $this->providers[$token];
            } else if ($this->injector) {
                //if parent injector exists
                //then recursive search in parent injector
                $provider = $this->injector->getProvider($token);
            } else {
                //until root injector has no provider
                throw new ProviderNotFoundException($token . '...provider not found');
            }

            return $provider;
        }

        public function bind(... $bindings)
        {
            foreach ($bindings as $binding) {
                if (is_array($binding)) {
                    array_walk($binding, [$this, 'bind']);
                } else if ($binding instanceof Binding) {
                    $this->providers[$binding->getToken()] = $binding->getProvider();
                }
            }

        }

        public function getChild($providers = [])
        {
            $child = new self($this);
            $child->providers($providers);
            return $child;
        }


        function __call($name, $arguments)
        {
            $this->provide($name,$arguments[0]);
        }

        /**
         *
         */
        function __invoke(string $name)
        {
           return $this->get($name);
        }
    }
}