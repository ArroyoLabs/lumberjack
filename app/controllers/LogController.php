<?php
namespace app\controllers;

class LogController extends \erdiko\controllers\Web
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
    public function getDetail($request, $response, $args)
    {
        $eventID = (int)$args['param'];

        $logService = new \app\models\LogService($this->container->em);
        $eventDetails = $logService->getEventDetail($eventID);

        //var_dump($eventDetails); die("details");
        $entries = array();
        foreach($eventDetails as $detail) {
            $res['userID'] = $detail->getUsersId();
            $res['value'] = $detail->getValue();
            $res['time'] = $detail->getCreatedAt();
            $res['message'] = "this portion needs a little bit of work";
            $entries[] = $res;
        }

        //var_dump($entries); die("entries");

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
