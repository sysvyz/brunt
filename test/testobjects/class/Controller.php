<?php
/**
 * Created by PhpStorm.
 * User: mb
 * Date: 17.04.16
 * Time: 03:16
 */

namespace SVZ;


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
            RequestService::class => ClassProvider::init(RequestService::class),
            ServiceY::class => ClassProvider::init(ServiceY::class),
            ServiceZ::class => ClassProvider::init(ServiceZ::class),
            Request::class => ClassProvider::init(Request::class),
        ] + parent::_DI_PROVIDERS();
    }
}



class ControllerA extends Controller
{
    /**
     * @var RequestService
     */
    public $requestService;
    /**
     * @var ServiceZ
     */
    public $serviceZ;

    /**
     * ServiceY constructor.
     * @param Request $request
     * @param RequestService $requestService
     * @param ServiceZ $serviceZ
     */
    public function __construct(Request $request , RequestService $requestService, ServiceZ $serviceZ)
    {
        parent::__construct($request);
        $this->requestService = $requestService;
        $this->serviceZ = $serviceZ;
    }


}

class ControllerB extends Controller
{
    /**
     * @var RequestService
     */
    public $requestService;
    /**
     * @var RequestService
     */
    public $serviceY;

    /**
     * ServiceY constructor.
     * @param RequestService $requestService
     * @param RequestService $serviceY
     */
    public function __construct(Request $request , RequestService $requestService, ServiceY $serviceY)
    {
        parent::__construct($request);
        $this->requestService = $requestService;
        $this->serviceY = $serviceY;
    }
}
