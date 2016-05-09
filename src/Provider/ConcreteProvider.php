<?php

namespace Brunt\Provider {


    /**
     * Created by PhpStorm.
     * User: mb
     * Date: 08.05.16
     * Time: 23:56
     */
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