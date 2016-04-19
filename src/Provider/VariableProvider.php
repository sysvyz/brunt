<?php
/**
 * Created by PhpStorm.
 * User: mb
 * Date: 19.04.16
 * Time: 12:24
 */

namespace Prescription\Provider;


use Prescription\Injector;

class VariableProvider implements ProviderInterface
{
    /**
     * @var
     */
    private $value;


    /**
     * VariableProvider constructor.
     */
    public function __construct($value)
    {
        $this->value = $value;
    }

    function __invoke(Injector $injector)
    {
       return $this->value;
    }

    public static function init($value){
        return self($value);
    }
}