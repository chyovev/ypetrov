<?php
use Doctrine\Common\EventManager;
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Events;

require_once('autoload_doctrine_classes.php');

$entitiesFolder = [DOCTRINE_PATH . '/xml'];
$proxiesFolder  =  DOCTRINE_PATH . '/proxies';

// database params are moved to config.php

// register comment event subscriber for validation and mail notifications
$eventManager   = new EventManager();
$eventManager->addEventSubscriber(new CommentSubscriber());
$eventManager->addEventSubscriber(new ContactMessageSubscriber());

$config         = Setup::createXMLMetadataConfiguration($entitiesFolder, !IS_DEV, $proxiesFolder);
$entityManager  = EntityManager::create($dbParams[!IS_DEV], $config, $eventManager);
