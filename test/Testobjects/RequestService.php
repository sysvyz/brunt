<?php


namespace BruntTest\Testobjects;

use Brunt\Injectable;
use Brunt\Injector;
use Brunt\Provider\Classes\ClassProvider;
use Brunt\Provider\ValueProvider;

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
            '%BASE_URL%' => ValueProvider::init('http://www.sysvyz.org'),
            ServiceY::class => ClassProvider::init(ServiceY::class),
        ] + parent::_DI_PROVIDERS();
    }


}