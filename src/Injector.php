<?php
namespace {
    require_once __DIR__.'/functions.php';
}
namespace Brunt {

    use Brunt\Cache\ProxyCache;
    use Brunt\Exception\ProviderNotFoundException;
    use Brunt\Provider\Classes\ClassProvider;
    use Brunt\Provider\Contract\ProviderInterface;
    use Brunt\Provider\Lazy\LazyProxyBuilder;

    class Injector
    {

        private $injector = null;
        private $providers = [];

        /**
         * Injector constructor.
         * @param $injector
         */
        public function __construct(Injector $injector = null)
        {

            if (!$injector) {
                $this->bind(
                    Binding::init(LazyProxyBuilder::class)->toValue(LazyProxyBuilder::init()),
                    bind(ProxyCache::class)->toValue(ProxyCache::init())
                );
            }else{
                $this->injector = $injector;
            }
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

        /**
         * @return Injector parent injector
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

            return $provider->get($this);
        }

        /**
         * @param string $token
         * @return ProviderInterface instance
         */
        public function getProvider(string $token)
        {
            if (isset($this->providers[$token])) {
                //if provider exists this injector is responsible
                //execute provider
                $provider = $this->providers[$token];
            } else if ($this->injector) {
                //if parent injector exists
                //recursive search in parent injector
                $provider = $this->injector->getProvider($token);
                $this->providers[$token] = $provider;
            } else if (class_exists($token)) {
                //root injector has no provider
                $provider = new ClassProvider($token);
                $this->provide($token, $provider);
                $this->providers[$token] = $provider;
            } else {
                throw new ProviderNotFoundException($token . '...provider not found');

            }

            return $provider;
        }

        public function provide(string $token, ProviderInterface $callable)
        {
            $this->providers[$token] = $callable;
        }

        public function getChild($providers = [])
        {
            $child = new self($this);
            $child->providers($providers);
            return $child;
        }

        public function providers(array $providers = [])
        {
            foreach ($providers as $name => $provider) {
                if (is_int($name) && is_string($provider)) {
                    $this->provide($provider, ClassProvider::init($provider));
                } else if ($provider instanceof ProviderInterface) {
                    $this->provide($name, $provider);
                } else if (is_string($provider) && class_exists($provider)) {
                    $this->provide($name, ClassProvider::init($provider));
                }
            }
        }

        function __call($name, $arguments)
        {
            $this->provide($name, $arguments[0]);
        }

        /**
         * @param array $bindings
         */
        function __invoke(... $bindings)
        {
            $this->bind($bindings);
        }
    }
}