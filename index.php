<?php



use Brunt\Injector;
use Brunt\Provider\Classes\ClassProvider;
use BruntTest\Testobjects\Controller;
use BruntTest\Testobjects\ControllerA;

include_once 'fileloader.php';

$inj = new Injector();


$inj->provide(Controller::class, ClassProvider::init(ControllerA::class));

$inj->provide('%BASE_URL%', function () {
    return 'http://sysvyz.org/';
});

echo '<pre>';
echo '-----------------------'.PHP_EOL;
echo 'Injector:'.PHP_EOL;
var_dump($inj);
echo '-----------------------'.PHP_EOL;
echo 'Controller:'.PHP_EOL;
var_dump($inj->get(Controller::class));
echo '</pre>';
