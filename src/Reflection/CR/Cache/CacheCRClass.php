<?php


namespace Brunt\Reflection\CR\Cache;


use Brunt\Reflection\CR\CRClass;
use Brunt\Reflection\CR\CRField;
use Brunt\Reflection\CR\CRMethod;

class CacheCRClass implements CRClass
{


    private $data;

    /**
     * CacheCRClass constructor.
     * @param $data
     */
    public function __construct($data)
    {
        print_r($data['getClassName']);
        echo PHP_EOL;

        $this->data = $data;

        $methods = [];
        foreach ( $this->data['getMethods'] as $name => $method){
            $methods[$name] = new CacheCRMethod($method);
        }

        $this->data['getMethods'] = $methods;
        $fields = [];
        foreach ( $this->data['getFields'] as $name => $field){
            $fields[$name] = new CacheCRField($field);
        }

        $this->data['getFields'] = $fields;
        $this->data['getConstructor'] = new CacheCRMethod($this->data['getConstructor']);
        //echo $this->getClassName().PHP_EOL;
    }


    /**
     * @return \ReflectionClass
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * @param \ReflectionClass $class
     */
    public function setClass($class)
    {
        $this->class = $class;
    }

    public function toArray()
    {
        return [
            'getClassName' => $this->getClassName(),
            'getConstructor' => $this->getConstructor(),
            'hasDependencies' => $this->hasDependencies(),
            'hasProviders' => $this->hasProviders(),
            'getModifiers' => $this->getModifiers(),
            'getMethods' => array_map(function (CRMethod $method) {
                return $method->toArray();
            }, $this->getMethods()),
            'getFields' => array_map(function (CRField $field) {
                return $field->toArray();
            }, $this->getFields()),
        ];
    }

    /**
     * @return string
     */
    public function getClassName():string
    {
        return $this->data['getClassName'];
    }

    /**
     * @return array
     */
    public function getModifiers()
    {
        return $this->data['getModifiers'];
    }

    /**
     * @return CRMethod[]
     */
    public function getMethods():array
    {
        return $this->data['getMethods'];
    }

    /**
     * @return CRField[]
     */
    public function getFields()
    {
        return $this->data['getFields'];
    }


    /**
     * @return \ReflectionClass
     */
    public function hasProviders()
    {
        return $this->data['hasProviders'];
    }

    /**
     * @return \ReflectionClass
     */
    public function hasDependencies()
    {
        return $this->data['hasDependencies'];
    }

    /**
     * @return CRField[]
     */
    public function getFileName()
    {
        return $this->data['getFileName'];
    }

    public function getConstructor()
    {
        return $this->data['getConstructor'];
    }
}
