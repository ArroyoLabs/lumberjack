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

    public function addIntLog($post) 
    {
        //var_dump($post); die('addintLog');

        $logEntity = new \app\entities\LogValueNumber;

        if(!property_exists($post,'event_id') || empty($post->event_id)) {
            throw new \Exception("Users ID is required");
        }

        if(!property_exists($post,'users_id') || empty($post->users_id)) {
            throw new \Exception("Users ID is required");
        }

        if(!property_exists($post,'number_value') || empty($post->number_value)) {
            throw new \Exception("Numbered Value is is required");
        }

        if(!property_exists($post,'created_at') || empty($post->created_at)) {
            throw new \Exception("Created Timestamp is required");
        }

        $logEntity->setEventId($post->event_id);
        $logEntity->setUsersId($post->users_id);
        $logEntity->setValue($post->number_value);
        $logEntity->setCreatedAt($post->created_at);

    }

    public function addStatusLog($post)
    {
        
    }

}