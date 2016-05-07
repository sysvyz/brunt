<?php
/**
 * Created by PhpStorm.
 * User: mb
 * Date: 14.04.16
 * Time: 17:36
 */
namespace Brunt;
use Brunt\Provider\ClassProvider;
use BruntTest\Testobjects\Car;
use BruntTest\Testobjects\Controller;
use BruntTest\Testobjects\ControllerA;
use BruntTest\Testobjects\Engine;
use BruntTest\Testobjects\FastCar;
use BruntTest\Testobjects\HeavyEngine;

include_once 'fileloader.php';


$inj = new Injector();
$inj->provide(Car::class, ClassProvider::init(FastCar::class));
$inj->provide(Engine::class, ClassProvider::init(HeavyEngine::class));
$inj->provide(Controller::class, ClassProvider::init(ControllerA::class));
$inj->provide('%BASE_URL%', function () {
    return 'http://sysvyz.org/';
});

var_dump($inj->get(Car::class));
var_dump($inj->get(Controller::class));
