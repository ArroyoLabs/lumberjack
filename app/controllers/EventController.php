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

        $eventsList = array();
        foreach ($events as $event){
            $res['eventID'] = $event->getId();
            $res['description'] = $event->getDescription();
            $res['name'] = $event->getName();
            $res['detail_href'] = '/event/detail/' . $res['eventID'];
            $res['update_href'] = '/event/update/' . $res['eventID'];
            $res['image_src'] = (!empty($event->getImage())) ? '/images/events/' . $event->getImage() : '/images/placeholder.png';
            $res['latest_update'] = "Last update by aPerson x minutes ago";
            $eventsList[] = $res;
        }

        $view = 'layouts/event.html';
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
            'title' => "Events Index",
            'description' => "This is the index page of all the events that were created.",
            'eventsList' => $eventsList
        ];

        return $this->container->theme->render($response, $view, $themeData);
    }

    /**
     *
     * Load the create page
     */
    public function getCreate($request, $response, $args)
    {

        $view = 'layouts/createevent.html';
        $themeData['theme'] = \erdiko\theme\Config::get($this->container->get('settings')['theme']);

        $themeData['page'] = [
            'title' => "Create Events",
            'description' => "Define your new events here.",

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
            
            //unit_value is not available in the string type event
            if($_POST['event_table_name'] == "event_value_string") {
                $_POST['unit_value'] = "N/A";
            }

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
        $view = 'layouts/updateevent.html';

        $eventId = $args["param"];

        $eventService = new \app\models\EventService($this->container->em);
        $eventDetails = $eventService->getEventDetail($eventId);
        

        //Handles Img
        $eventImg = (!empty($eventDetails["event"]->getImage())) ? '/images/events/' . $eventDetails["event"]->getImage() : '/images/placeholder.png';//$eventDetails["event"]->getImage();

        //Handles Forms
        $eventName = $eventDetails["event"]->getName();
        $eventDescription = $eventDetails["event"]->getDescription();
        $eventTemplate = $eventDetails["event"]->getTemplate();
        $eventValueUnit = $eventDetails["event"]->getValueUnit();


        $themeData['theme'] = \erdiko\theme\Config::get($this->container->get('settings')['theme']);

        $themeData['page'] = [
            'title' => "Edit Events",
            'description' => "Edit your already created events here!",
            'event_id' => $eventId,
            'img_src' => $eventImg,
            'event_name' => $eventName,
            'event_description' => $eventDescription,
            'event_template' => $eventTemplate,
            'event_unit' => $eventValueUnit
        ];


        return $this->container->theme->render($response, $view, $themeData);

    }

    public function postUpdate($request, $response, $args)
    {
        try {

            $eventService = new \app\models\EventService($this->container->em);

            $now = time();
            $date = date("Y-m-d h:i:s", $now);

            $_POST['updated_at'] = $date;

            $params = (Object)array_filter($_POST);	// treat this as object
            
            // service method call and pass $params
            
            $result = $eventService->editEvent($params);

            $this->redirect('/event');
            
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

        //var_dump($eventID); die('entries');
        $eventService = new \app\models\EventService($this->container->em);
        $eventDetails = $eventService->getEventDetail($eventID);

        //var_dump($eventDetails); die('entries');

        $entries = array();
        foreach($eventDetails['logs'] as $detail) {
            $res['userID'] = $detail->getUsersId();
            $res['value'] = $detail->getValue();
            $res['time'] = $detail->getCreatedAt();
            $res['message'] = "this portion needs a little bit of work";
            $entries[] = $res;
        }

        //var_dump($entries); die('entries');
        $this->container->logger->debug("/controller");
        $view = 'layouts/eventdetail.html';

        $themeData['theme'] = \erdiko\theme\Config::get($this->container->get('settings')['theme']);
        
        
        $this->container->logger->debug("param: ".$eventID);

        $themeData['page'] = [
            'event_name' => $eventDetails['event_name'],
            'description' => "Description of the log you just clicked yourself.",
            'entries' => $entries,
            'createlog_href' => '/log/create/' . $eventID
        ];
    
        return $this->container->theme->render($response, $view, $themeData);
    }

}
