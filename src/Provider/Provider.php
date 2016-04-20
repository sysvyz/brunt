<?php
/**
 * Created by PhpStorm.
 * User: mb
 * Date: 17.04.16
 * Time: 01:28
 */

namespace Prescription\Provider;


use Prescription\Injector;

interface  Provider
{
    function __invoke(Injector $injector);
}
