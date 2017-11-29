<?php
/**
 * Event Model
 *
 * Model for Event
 *
 * @category    app
 * @package     models
 * @copyright   Copyright (c) 2017, Arroyo Labs, http://www.arroyolabs.com
 * @author      John Arroyo, john@arroyolabs.com
 */
namespace app\models;

class EventService
{

    protected $_em = null;

    /**
     *
     *
     */
    public function __construct(\Doctrine\ORM\EntityManager $em)
    {
        $this->_em = $em;
    }

    /**
     *
     *
     */
    public function getEvents()
    {
        return $this->_em->getRepository('app\entities\Event')->findAll();
    }


    public function editEvent($post)
    {

        if(empty($post->event_id)) {
            throw new \Exception("EventID is required");
        }

        //TODO Store
        $eventEntity = $this->_em->getRepository('app\entities\Event')
                         ->findOneBy(array("id" => $post->event_id)); 

        if(property_exists($post,'event_name') || !empty($post->event_name)) {
            $eventEntity->setName($post->event_name);
        }

        if(property_exists($post,'event_description') || !empty($post->event_description)) {
            $eventEntity->setDescription($post->event_description);
        }

        if(property_exists($post,'event_template') || !empty($post->event_template)) {
            $eventEntity->setTemplate($post->event_template);
        }

        if(property_exists($post,'event_unit') || !empty($post->event_unit)) {
            $eventEntity->setValueUnit($post->event_unit);
        }
        

        $eventEntity->setUpdatedAt($post->updated_at);


        $this->_em->persist($eventEntity);
        
        $this->_em->flush();
    }

    public function addEvent($post) 
    {
        $eventEntity = new \app\entities\Event;

        //do created at and updated at here

        //DO SOME ERROR CHECKING HERE

        if(!property_exists($post,'users_id') || empty($post->users_id)) {
            throw new \Exception("Users ID is required");
        }

        if(!property_exists($post,'event_title') || empty($post->event_title)) {
            throw new \Exception("Event Title is required");
        }

        if(!property_exists($post,'event_description') || empty($post->event_description)) {
            throw new \Exception("Event Description is required");
        }

        if(!property_exists($post,'template') || empty($post->template)) {
            throw new \Exception("Template is required");
        }

        if(!property_exists($post,'unit_value') || empty($post->unit_value)) {
            throw new \Exception("Unit Value is required");
        }

        if(!property_exists($post,'created_at') || empty($post->created_at)) {
            throw new \Exception("Created Timestamp is required");
        }

        if(!property_exists($post,'updated_at') || empty($post->updated_at)) {
            throw new \Exception("Updated Timestamp is required");
        }

        if(!property_exists($post,'event_table_name') || empty($post->event_table_name)) {
            throw new \Exception("Updated Timestamp is required");
        }
         
        $eventEntity->setUsersId($post->users_id);
        $eventEntity->setName($post->event_title);
        $eventEntity->setDescription($post->event_description);
        $eventEntity->setTemplate($post->template);
        $eventEntity->setValueUnit($post->unit_value);
        $eventEntity->setCreatedAt($post->created_at);
        $eventEntity->setUpdatedAt($post->updated_at);
        $eventEntity->setEventTableName($post->event_table_name);

        
        $this->_em->persist($eventEntity);

        return $this->_em->flush();

    }

    public function getEventDetail($eventID)
    {
        if(empty($eventID)) {
            throw new \Exception("EventID is required");
        }

        //TODO Store
        $result = array(); 
        $result["event"] = $this->_em->getRepository('app\entities\Event')
                         ->findOneBy(array("id" => $eventID)); 

        // TODO if event not found, throw an exception

        if(empty($result["event"])) {
            throw new \Exception("Event is not found");
        }

        $eventName = $result["event"]->getName();
        $eventType = $result["event"]->getEventTableName();

        $result["event_name"] = $eventName;


        //var_dump($eventType); die('eventType');

        if($eventType == "event_value_number") {
            $result["logs"] = $this->_em->getRepository('app\entities\EventValueNumber')
                ->findBy(array("event_id" => $eventID), array('created_at' => 'DESC'));
        } else if($eventType == "event_value_string") {
            $result["logs"] = $this->_em->getRepository('app\entities\EventValueString')
                ->findBy(array("event_id" => $eventID), array('created_at' => 'DESC'));
        }
        

        return $result; 
    }

    public function uploadImg($file = null, $eventId = null) {

        $eventEntity = new \app\entities\Event;

        $fileExt = explode("/", $_FILES['file']['type'])[1];
        $fileName = "event" . $eventId .'.'. $fileExt;
        $fileTemp = $_FILES['file']['tmp_name'];
        
        $validFileExt = ["gif", "jpeg", "jpg", "png"];

        if(empty($file) ) {
            throw new \Exception("The file is empty");
        }

        if(empty($eventId)){
            throw new \Exception("Event ID is required");
        }

        if(!in_array($fileExt, $validFileExt)) {
            throw new \Exception("Invalid File Extension");
        }

        move_uploaded_file($fileTemp, "../var/uploads/".$fileName);

        copy("../var/uploads/".$fileName, "../public/images/events/".$fileName);


        $entity = $this->_em->getRepository('app\entities\Event')
                       ->findBy(array("id" => $eventId));
            

        $entity[0]->setImage($fileName);

        $this->_em->persist($entity[0]);

        $this->_em->flush();
    }

}
