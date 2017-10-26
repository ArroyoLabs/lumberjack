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
        $logService = new \app\models\LogService($this->container->em);
        
        $params = (Object) array_filter($_POST);

        $logService->addIntLog($params);

        
    }

    function postAddStatus($request, $response, $args)
    {
        var_dump($_POST); die('statusLog');
    }

}