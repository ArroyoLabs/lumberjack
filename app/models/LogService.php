<?php
/**
 * Log Model
 *
 * Model for Log
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

    public function addLog($post)
    {
        if(property_exists($post, "number_value")) {
            $entity = $this->addNumberLog($post);
        } else if(property_exists($post, "string_value")) {
            $entity = $this->addStringLog($post);
        } else {
            throw new \Exception("Event Type identifier (number or string) is required");
        }

        $this->_em->persist($entity);
        $this->_em->flush();
    }

    public function addNumberLog($post) 
    {
        //var_dump($post); die('post');
        $logEntity = new \app\entities\EventValueNumber;

        if(!property_exists($post,'event_id') || empty($post->event_id)) {
            throw new \Exception("Event ID is required");
        }

        if(!property_exists($post,'user_id') || empty($post->user_id)) {
            throw new \Exception("Users ID is required");
        }

        if(!property_exists($post,'created_at') || empty($post->created_at)) {
            throw new \Exception("Created Timestamp is required");
        }

        $logEntity->setEventId($post->event_id);
        $logEntity->setUsersId($post->user_id);
        $logEntity->setValue($post->number_value);
        $logEntity->setCreatedAt($post->created_at);

        return $logEntity;
    }

    public function addStringLog($post)
    {
        $logEntity = new \app\entities\EventValueString;

        if(!property_exists($post,'event_id') || empty($post->event_id)) {
            throw new \Exception("Users ID is required");
        }

        if(!property_exists($post,'user_id') || empty($post->user_id)) {
            throw new \Exception("Users ID is required");
        }

        if(!property_exists($post,'created_at') || empty($post->created_at)) {
            throw new \Exception("Created Timestamp is required");
        }

        $logEntity->setEventId($post->event_id);
        $logEntity->setUsersId($post->user_id);
        $logEntity->setValue($post->string_value);
        $logEntity->setCreatedAt($post->created_at);

        return $logEntity;
    }

}