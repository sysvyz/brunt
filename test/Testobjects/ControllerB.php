<?php


namespace BruntTest\Testobjects;



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
