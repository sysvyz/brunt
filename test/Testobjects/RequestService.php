<?php
/**
 * Created by PhpStorm.
 * User: mb
 * Date: 17.04.16
 * Time: 14:45
 */

namespace PrescriptionTest\Testobjects;

use Prescription\Injectable;
use Prescription\Injector;
use Prescription\Provider\ClassProvider;

class Service extends Injectable
{

}

class RequestService extends Service
{

    private static $COUNTER = 0;

    public $count;
    public $url;

    /**
     * requestService constructor.
     */
    public function __construct(string $url)
    {
        $this->count = self::$COUNTER++;
        $this->url = $url;
    }


    public static function _DI_DEPENDENCIES()
    {
        return [
            'url' => '%BASE_URL%'
        ] + parent::_DI_DEPENDENCIES();
    }
}

class ServiceY extends Service
{
    /**
     * @var RequestService
     */
    public $requestService;

    /**
     * ServiceY constructor.
     * @param RequestService $requestService
     */
    public function __construct(RequestService $requestService)
    {
        $this->requestService = $requestService;
    }



    public static function _DI_PROVIDERS()
    {
        return [
            RequestService::class => ClassProvider::init(RequestService::class),

        ] + parent::_DI_PROVIDERS();
    }

}

class ServiceZ extends Service
{

    /**
     * @var RequestService
     */
    public $requestService;
    /**
     * @var RequestService|ServiceY
     */
    public $serviceY;

    /**
     * ServiceY constructor.
     * @param RequestService $requestService
     * @param RequestService $serviceY
     */
    public function __construct(RequestService $requestService, ServiceY $serviceY)
    {
        $this->requestService = $requestService;
        $this->serviceY = $serviceY;
    }

    public static function _DI_PROVIDERS()
    {
        return [
            //   requestService::class => ClassProvider::init(requestService::class),
            '%BASE_URL%' => function (Injector $i) {
                return 'http://www.sysvyz.org';
            },
            ServiceY::class => ClassProvider::init(ServiceY::class),
        ] + parent::_DI_PROVIDERS();
    }


}