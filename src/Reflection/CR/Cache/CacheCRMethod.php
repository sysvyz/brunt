<?php


namespace Brunt\Reflection\CR\Cache;

use Brunt\Reflection\CR\CRMethod;
use Brunt\Reflection\CR\CRParam;
use Reflection;

class CacheCRMethod  implements CRMethod
{
    private $data;

    public function __construct($data)
    {

        $this->data = $data;

       $params = [];
        foreach ( $this->data['getParams'] as $name => $param){
            $params[$name] = new CacheCRParam($param);
        }

        $this->data['getParams'] = $params;

    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->data['getName'];
    }

    /**
     * @return CRParam[]
     */
    public function getParams()
    {
        return $this->data['getParams'];
    }

    /**
     * @return boolean
     */
    public function isPrivate()
    {
        return $this->data['isPrivate'];
    }

    /**
     * @return boolean
     */
    public function isPublic()
    {
        return $this->data['isPublic'];
    }

    /**
     * @return boolean
     */
    public function isProtected()
    {
        return $this->data['isProtected'];
    }

    /**
     * @return boolean
     */
    public function isStatic()
    {

        return $this->data['isStatic'];
    }

    /**
     * @return array
     */
    public function getModifiers()
    {
        return $this->data['getModifiers'];
    }

    public function toArray()
    {
        return [
            'getModifiers' => $this->getModifiers(),
            'getParams' =>  array_map( function (CRParam $param) {
                return $param->toArray();
            },$this->getParams()),
            'isStatic' =>$this->isStatic(),
            'isProtected' =>$this->isProtected(),
            'isPublic' =>$this->isPublic(),
            'isPrivate' =>$this->isPrivate(),
            'getName' =>$this->getName()
        ];
    }
}