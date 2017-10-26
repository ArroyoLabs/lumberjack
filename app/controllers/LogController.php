<?php
namespace app\controllers;

class LogController extends \erdiko\controllers\Web
{
    
    use \erdiko\theme\traits\Controller;

    public function get($request, $response, $args)
    {
    }


    function postAddInt($request, $response, $args)
    {
        var_dump($_POST); die('numberedLog');
    }

    function postAddStatus($request, $response, $args)
    {
        var_dump($_POST); die('statusLog');
    }

}