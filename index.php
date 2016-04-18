<?php
/**
 * Created by PhpStorm.
 * User: mb
 * Date: 17.04.16
 * Time: 18:14
 */


use SVZ\ClassProvider;
use SVZ\Controller;
use SVZ\ControllerA;

include_once 'fileloader.php';

$inj = new \SVZ\Injector();


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
