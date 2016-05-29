<?php

namespace Brunt\Provider {


    use Brunt\Provider\I\ProviderInterface;
    use Brunt\Provider\Lazy\LazyProvider;
    use Brunt\Provider\Singleton\SingletonProvider;

    abstract class ConcreteProvider implements ProviderInterface
    {
        
        public function singleton(){
            return new SingletonProvider($this);
        }
        public function lazy(){
            return new LazyProvider($this);
        }


    }
}