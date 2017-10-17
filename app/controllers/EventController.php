<?php
namespace app\controllers;

class EventController extends \erdiko\controllers\Web
{
    
    use \erdiko\theme\traits\Controller;

    /**
     *
     *
     */
    public function get($request, $response, $args)
    {
        $logService = new \app\models\LogService($this->container->em);
        $events = $logService->getEvents();

        $logEvents = array();
        foreach ($events as $event){
            $res['eventID'] = $event->getId();
            $res['description'] = $event->getDescription();
            $res['name'] = $event->getName();
            $res['href'] = '/log/detail/' . $res['eventID'];
            $res['image_src'] = 'https://lorempixel.com/600/300/food/5/';
            $res['latest_update'] = "Last update by aPerson x minutes ago";
            $logEvents[] = $res;
        }

        $view = 'layouts/log.html';
        $themeData['theme'] = \erdiko\theme\Config::get($this->container->get('settings')['theme']);

        /*
        title
        eventID
        description
        image_src
        latest_update
        href
        */

        $themeData['page'] =  [
            'title' => "This is the Log Index Controller",
            'description' => "This is where all the log that were previously created",
            'logevents' => $logEvents
        ];

        return $this->container->theme->render($response, $view, $themeData);
    }

    /**
     *
     *
     */
    public function getCreate($request, $response, $args)
    {

        $view = 'layouts/create.html';
        $themeData['theme'] = \erdiko\theme\Config::get($this->container->get('settings')['theme']);

        $themeData['page'] = [
            'title' => "This is the Log Edit Controller",
            'description' => "This is where all the log we want are to be created",

        ];

        return $this->container->theme->render($response, $view, $themeData);
    }

    public function postCreate($request, $response, $args)
    {

        try {
            
            $logService = new \app\models\LogService($this->container->em);

            //collect id of the one logged in
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
            $this->redirect('/event'); //header("Location: /log"); exit;
            
        } catch (\Exception $e) {
            var_dump($e);
        }
    
    }

    /**
     *
     *
     */
    public function getDetail($request, $response, $args)
    {
        $eventID = (int)$args['param'];

        $logService = new \app\models\LogService($this->container->em);
        $eventDetails = $logService->getEventDetail($eventID);

        $entries = array();
        foreach($eventDetails as $detail) {
            $res['userID'] = $detail->getUsersId();
            $res['value'] = $detail->getValue();
            $res['time'] = $detail->getCreatedAt();
            $res['message'] = "this portion needs a little bit of work";
            $entries[] = $res;
        }

        $this->container->logger->debug("/controller");
        $view = 'layouts/logdetail.html';

        $themeData['theme'] = \erdiko\theme\Config::get($this->container->get('settings')['theme']);
        
        
        $this->container->logger->debug("param: ".$eventID);

        $themeData['page'] = [
            'title' => "Custom Log Stuff",
            'description' => "Description of the log you just clicked yourself.",
            'entries' => $entries
        
        ];
    
        return $this->container->theme->render($response, $view, $themeData);
    }

}
