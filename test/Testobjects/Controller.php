<?php
/**
 * Created by PhpStorm.
 * User: mb
 * Date: 17.04.16
 * Time: 03:16
 */

namespace BruntTest\Testobjects;


use Brunt\Injectable;
use Brunt\Provider\ClassProvider;

class Controller extends Injectable
{
    public $request;


    /**
     * Controller constructor.
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public static function _DI_PROVIDERS()
    {
        return [
            RequestService::class => ClassProvider::init(RequestService::class)->singleton(),
            ServiceY::class => ClassProvider::init(ServiceY::class)->singleton(),
            ServiceZ::class => ClassProvider::init(ServiceZ::class)->singleton(),
            Request::class => ClassProvider::init(Request::class)->singleton(),
        ] + parent::_DI_PROVIDERS();
    }
}


