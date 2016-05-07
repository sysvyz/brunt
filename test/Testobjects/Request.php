<?php
/**
 * Created by PhpStorm.
 * User: mb
 * Date: 17.04.16
 * Time: 17:45
 */

namespace BruntTest\Testobjects;


use Brunt\Injectable;

class Request extends Injectable
{

    private $post;
    private $get;
    private $request;

    /**
     * Request constructor.
     */
    public function __construct()
    {
        $this->post = $_POST;
        $this->get = $_GET;
        $this->request = $_REQUEST;
    }
}