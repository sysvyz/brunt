<?php
/**
 * Created by PhpStorm.
 * User: mb
 * Date: 19.05.16
 * Time: 22:07
 */

namespace Brunt\Reflection\CR\Renderer;


abstract class CRRenderer
{

    abstract public function render();
    public function __toString(){
       return $this->render();
    }

}