<?php
/**
 * Log Model
 *
 * Model for Logs
 *
 * @category    app
 * @package     models
 * @copyright   Copyright (c) 2017, Arroyo Labs, http://www.arroyolabs.com
 * @author      John Arroyo, john@arroyolabs.com
 */
namespace app\models;

class LogService
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
        //die("eventdetail");
        return $this->_em->getRepository('app\entities\EventValueNumber')
                         ->findBy(array("event_id" => $eventID), array('created_at' => 'DESC'));
    }

}
