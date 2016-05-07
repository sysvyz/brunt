<?php
/**
 * Created by PhpStorm.
 * User: mb
 * Date: 18.04.16
 * Time: 14:07
 */

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
