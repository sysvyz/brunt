<?php
/**
 * Created by PhpStorm.
 * User: mb
 * Date: 17.04.16
 * Time: 01:28
 */

namespace SVZ;


interface  ProviderInterface
{
    function __invoke(Injector $injector);
}
