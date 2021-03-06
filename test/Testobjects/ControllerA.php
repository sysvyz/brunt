<?php


namespace BruntTest\Testobjects;


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