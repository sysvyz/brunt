<?php
/**
 * Created by PhpStorm.
 * User: mb
 * Date: 14.04.16
 * Time: 17:36
 */
namespace Brunt;
include_once 'fileloader.php';


$inj = new Injector();
$inj->provide(Car::class, ClassProvider::init(FastCar::class));
$inj->provide(Engine::class, ClassProvider::init(ZendEngine::class));
$inj->provide(Controller::class, ClassProvider::init(ControllerA::class));
$inj->provide('%BASE_URL%', function () {
    return 'http://sysvyz.org/';
});

var_dump($inj->get(Car::class));
var_dump($inj->get(Controller::class));
