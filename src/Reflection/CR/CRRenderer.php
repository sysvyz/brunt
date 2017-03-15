<?php namespace Brunt\Reflection\CR;

abstract class CRRenderer
{

    abstract public function render();
    public function __toString(){
       return $this->render();
    }

}