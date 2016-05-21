<?php

namespace Brunt\Provider {


    abstract class ConcreteProvider implements Provider
    {
        
        public function singleton(){
            return new SingletonProvider($this);
        }
        public function lazy(){
            return new LazyProvider($this);
        }


    }
}