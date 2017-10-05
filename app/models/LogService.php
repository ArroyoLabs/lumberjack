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

}
