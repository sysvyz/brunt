<?php

namespace Brunt\Provider {


    use Brunt\Injector;
    use Brunt\Provider\Contract\ProviderInterface;
    use Brunt\Provider\Lazy\LazyProvider;
    use Brunt\Provider\Singleton\SingletonProvider;

    abstract class AbstractProvider implements ProviderInterface
    {
        
        public function singleton(){
            return new SingletonProvider($this);
        }
        public function lazy(){
            return new LazyProvider($this);
        }


    }
}