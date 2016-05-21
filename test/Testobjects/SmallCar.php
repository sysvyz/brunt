<?php


namespace BruntTest\Testobjects;

class SmallCar extends Car
{
    public static function _DI_PROVIDERS()
    {
        return [
            '%CAR_NAME%'=> function(){
                return 'SMALLCAR';
            },
        ]+parent::_DI_PROVIDERS();
    }


}