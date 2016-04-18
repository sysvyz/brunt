<?php
/**
 * Created by PhpStorm.
 * User: mb
 * Date: 18.04.16
 * Time: 01:22
 */

namespace SVZ;


class CircA extends Injectable
{

    /**
     * CircA constructor.
     */
    public function __construct(CircA $b)
    {

    }
}



class CircB extends Injectable
{


    /**
     * CircB constructor.
     * @param CircC $b
     */
    public function __construct(CircC $b)
    {

    }
}



class CircC extends Injectable
{

    /**
     * CircC constructor.
     * @param CircB $b
     */
    public function __construct(CircB $b)
    {

    }
}