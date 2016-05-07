<?php
/**
 * Created by PhpStorm.
 * User: mb
 * Date: 19.04.16
 * Time: 19:39
 */

namespace Brunt\Store;

interface StoreInterface
{
    public function put(string $name, $value);

    public function get($name);
}

class DataStore implements StoreInterface
{

    private $data = [];

    public function put(string $name, $value)
    {
        $this->data[$name] = $value;
    }

    public function get($name)
    {
        return $this->data[$name];
    }

    public function set(string $name, $value)
    {
        $this->put($name, $value);
    }

    function __get($name)
    {
        return $this->get($name);
    }

    function __set($name, $value)
    {
        $this->put($name, $value);
    }

    function __unset($name)
    {
        unset($this->data[$name]);
    }
}