<?php
namespace app\controllers;

class LogController extends \erdiko\controllers\Web
{
    
    use \erdiko\theme\traits\Controller;

    public function get($request, $response, $args)
    {
    }

    /**
     * Depending on the type of event, generate the corresponding form. (String or Integer)
     * 
     */
    public function getCreate($request, $response, $args)
    {
        $eventID = (int)$args['param'];

        $eventService = new \app\models\EventService($this->container->em);
        $eventDetails = $eventService->getEventDetail($eventID);
        $eventTableName = $eventDetails['event']->getEventTableName();


        if($eventTableName == "event_value_number") {

            $form = (object) array (
                "name" => "Numeric Log",
                "placeholder" => "Numeric Value"
            );

        } else if($eventTableName == "event_value_string") {

            $form = (object) array (
                "name" => "Status Log",
                "placeholder" => "Write the nature of the log here"
            );

        }

        $view = 'layouts/createlog.html';

        $themeData['theme'] = \erdiko\theme\Config::get($this->container->get('settings')['theme']);

        $themeData['page'] = [
            'event_id'         => $eventID,
            'event_name'       => $eventDetails['event_name'],
            'eventdetail_href' => '/event/detail/' . $eventID,
            'event_table_name' => "event_value_number",
            'form'             => $form
        ];



        return $this->container->theme->render($response, $view, $themeData);
    }

    public function postCreate($request, $response, $args)
    {
        var_dump($_POST); die('post');

        //if number value, set values to event_value_number

        //if string value, set values to event_value_string


    }


}