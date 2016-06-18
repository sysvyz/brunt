<?php


namespace Brunt\Reflection\CR\Reflective;


use Brunt\Reflection\CR\Cache\CacheCRMethod;
use Brunt\Reflection\CR\CRClass;
use Brunt\Reflection\CR\CRField;
use Brunt\Reflection\CR\CRMethod;
use Reflection;

class ReflectiveCRClass implements CRClass
{
    /**
     * @var \ReflectionClass
     */
    private $class;
    /**
     * @var CRMethod[]
     */
    private $methods;
    /**
     * @var CRField[]
     */
    private $fields;

    /**
     * RClass constructor.
     * @param \ReflectionClass $class
     * @param CRMethod[] $methods
     * @param CRField[] $fields
     */
    public function __construct(\ReflectionClass $class, array $methods, array $fields)
    {
        print_r(['build']);
        $this->class = $class;
        $this->methods = $methods;
        $this->fields = $fields;
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
            'getConstructor' => $this->getConstructor()->toArray(),
            'getFileName' => $this->getFileName(),
            'hasDependencies' => $this->hasDependencies(),
            'hasProviders' => $this->hasProviders(),
            'getModifiers' => $this->getModifiers(),
            'getMethods' => array_map( function (CRMethod $method) {
                return $method->toArray();
            },$this->getMethods()),
            'getFields' => array_map( function (CRField $field) {
                return $field->toArray();
            },$this->getFields()),
        ];
    }

    /**
     * @return string
     */
    public function getClassName():string
    {
        return $this->class->getName();
    }

    /**
     * @return array
     */
    public function getModifiers()
    {
        return Reflection::getModifierNames($this->class->getModifiers());
    }

    /**
     * @return CRMethod[]
     */
    public function getMethods():array
    {
        return $this->methods;
    }

    /**
     * @return CRField[]
     */
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * @param CRField[] $fields
     */
    public function setFields($fields)
    {
        $this->fields = $fields;
    }

    /**
     * @return \ReflectionClass
     */
    private function getReflectionClass()
    {
        return $this->class;
    }

    /**
     * @return CRField[]
     */
    public function getFileName()
    {
        return $this->class->getFileName();
    }
    public function hasProviders(){
        return $this->class->hasMethod('_DI_PROVIDERS');
    }

    public function hasDependencies()
    {
        return $this->class->hasMethod('_DI_DEPENDENCIES');
    }

    public function getConstructor()
    {
        $method = ($this->class->getConstructor());

        if(!$method){
            return new CacheCRMethod([
                'getParams'=>[],
                'getModifieres'=>[],
                'isStatic'=>false,
                'getName'=>'__construct',
                'isProtected'=>false,
                'isPrivate'=>false,
                'isPublic'=>true,
            ]);
        }

        $params = $method->getParameters();
        $ps = [];
        foreach ($params as $param) {
            $ps  [$param->getName()] = new ReflectiveCRParam($param->getName() . "", $param);
        }
        return new ReflectiveCRMethod($method,$ps);
    }
}
