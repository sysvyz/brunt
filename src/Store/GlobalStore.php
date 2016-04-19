<?php
/**
 * Created by PhpStorm.
 * User: mb
 * Date: 19.04.16
 * Time: 19:48
 */

namespace Prescription\Store;
/**
 * @return null|GlobalStore
 */
function GlobalStore()
{
    return GlobalStore::init();
}

class GlobalStore extends DataStore
{
    private static $instance = null;

    /**
     * GlobalStore constructor.
     * @param null $instance
     */
    private function __construct()
    {

    }

    public static function __callStatic($name, $arguments)
    {
        if (empty($arguments)) {
            return self::init()->get($name);
        } else if (sizeof($arguments) == 1) {
            return self::init()->set($name, $arguments[0]);
        }

    }


    public static function init()
    {
        if (!self::$instance) {
            $instance = new self();
            self::$instance = $instance;
        }
        return self::$instance;
    }


}