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
        $eventService = new \app\models\EventService($this->container->em);
        $events = $eventService->getEvents();

        $logEvents = array();
        foreach ($events as $event){
            $res['eventID'] = $event->getId();
            $res['description'] = $event->getDescription();
            $res['name'] = $event->getName();
            $res['detail_href'] = '/event/detail/' . $res['eventID'];
            $res['update_href'] = '/event/update/' . $res['eventID'];
            $res['image_src'] = '/images/events/' . $res['image'];
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
     * Load the create page
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

    /**
     *
     * Create new event page
     */
    public function postCreate($request, $response, $args)
    {

        try {
            
            $eventService = new \app\models\EventService($this->container->em);

            //collect id of the one logged in
            $_POST['users_id'] = '99';
            $_POST['event_table_name'] = "hardcoded for now";

            $now = time();
            $date = date("Y-m-d h:i:s", $now);

            $_POST['created_at'] = $date;
            $_POST['updated_at'] = $date;

            $params = (Object)array_filter($_POST);	// treat this as object

            // service method call and pass $params
            
            $result = $eventService->addEvent($params);

            $this->redirect('/event');
            
        } catch (\Exception $e) {
            var_dump($e);
        }
    
    }

    public function getUpdate($request, $response, $args)
    {
        $view = 'layouts/update.html';

        $eventId = $args["param"];

        $themeData['theme'] = \erdiko\theme\Config::get($this->container->get('settings')['theme']);

        $themeData['page'] = [
            'title' => "This is the Log Edit Controller",
            'description' => "This is where all the log we want are to be created",
            'event_id' => $eventId
        ];



        return $this->container->theme->render($response, $view, $themeData);

    }

    public function postUpdate($request, $response, $args)
    {
        try {

        } catch (\Exception $e) {
            var_dump($e); die('postUpdateError');
        }
    }

    public function postUpdateImg($request, $response, $args)
    {

        $eventId = $_POST['event_id'];
        $eventService = new \app\models\EventService($this->container->em);

        try {      
            $eventService->uploadImg($_FILES['file'], $eventId);

            $this->redirect('/event');
        } catch (\Exception $e) {
            var_dump($e); die('3');
        }
    }
    /**
     *
     *
     */
    public function getDetail($request, $response, $args)
    {
        $eventID = (int)$args['param'];

        $eventService = new \app\models\EventService($this->container->em);
        $eventDetails = $eventService->getEventDetail($eventID);

        //var_dump($eventDetails); die();

        $entries = array();
        foreach($eventDetails['logs'] as $detail) {
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
            'event_name' => $eventDetails['event_name'],
            'description' => "Description of the log you just clicked yourself.",
            'entries' => $entries
        
        ];
    
        return $this->container->theme->render($response, $view, $themeData);
    }

}
