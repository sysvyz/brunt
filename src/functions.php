<?php
/**
 * Created by PhpStorm.
 * User: mb
 * Date: 04.03.17
 * Time: 19:28
 */
namespace Brunt{


    /**
     * convenience function for bindings
     * @param string $token
     * @return \Brunt\Binding
     */
    function bind(string $token)
    {
        return new \Brunt\Binding($token);
    }

    /**
     * @param \Brunt\Injector|null $injector
     * @return \Brunt\Injector
     */
    function inject(\Brunt\Injector $injector = null)
    {
        return new \Brunt\Injector($injector);
    }

}