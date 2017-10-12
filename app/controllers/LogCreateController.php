<?php
namespace app\controllers;

class LogCreateController extends \erdiko\controllers\Web
{
    use \erdiko\theme\traits\Controller;

    public function get($request, $response, $args)
    {

        $view = 'layouts/create.html';
        $themeData['theme'] = \erdiko\theme\Config::get($this->container->get('settings')['theme']);

        $themeData['page'] = [
            'title' => "This is the Log Edit Controller",
            'description' => "This is where all the log we want are to be created",

        ];

        return $this->container->theme->render($response, $view, $themeData);
    }

    public function postCreateevent($request, $response, $args)
    {

        try {
            
            $logService = new \app\models\LogService($this->container->em);

            //Be able to choose Moderator USer ID (hardcoded as 99)
            $_POST['users_id'] = '99';
            $_POST['event_table_name'] = "hardcoded for now";

            $now = time();//new DateTime();
            $date = date("Y-m-d h:i:s", $now);

            //var_dump($date); die('date');
            $_POST['created_at'] = $date;
            $_POST['updated_at'] = $date;

            $params = (Object)array_filter($_POST);	// treat this as object

            // service method call and pass $params
            
            //SEE THE RESULT RETURN TRUE OR FALSE
            $result = $logService->addEvent($params);

            //I feel like there's a slim way of doing this
            header("Location: /log"); exit;
            
        } catch (\Exception $e) {

        }
    
    }
}