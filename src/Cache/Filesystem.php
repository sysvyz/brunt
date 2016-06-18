<?php
/**
 * Created by PhpStorm.
 * User: mb
 * Date: 13.06.16
 * Time: 00:07
 */

namespace Brunt\Cache;


class Filesystem extends \Symfony\Component\Filesystem\Filesystem
{
    private static $instance;

    public static function init()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }
}