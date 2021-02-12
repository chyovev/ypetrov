<?php
require_once(VENDOR_PATH . '/autoload.php');
require_once('autoload_classes.php');

use Doctrine\Common\EventManager;
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Events;

$entitiesFolder = [DOCTRINE_PATH . '/xml'];
$proxiesFolder  =  DOCTRINE_PATH . '/proxies';
$dbParams       = [
    // development db configuration
    [
        'driver'        => 'pdo_mysql',
        'host'          => 'localhost',
        'dbname'        => 'database',
        'user'          => 'username',
        'password'      => 'password',
        'charset'       => 'utf8mb4',
        'driverOptions' => ['1002' => "SET NAMES 'UTF8MB4' COLLATE 'utf8mb4_general_ci'"],
    ],
    // production db configuration
    [
        'driver'        => 'pdo_mysql',
        'host'          => 'localhost',
        'dbname'        => 'database',
        'user'          => 'username',
        'password'      => 'password',
        'charset'       => 'utf8mb4',
        'driverOptions' => ['1002' => "SET NAMES 'UTF8MB4' COLLATE 'utf8mb4_general_ci'"],
    ],
];

// register comment event subscriber for validation
$eventManager   = new EventManager();
$eventManager->addEventSubscriber(new CommentValidatorSubscriber());

$config         = Setup::createXMLMetadataConfiguration($entitiesFolder, IS_DEV, $proxiesFolder);
$entityManager  = EntityManager::create($dbParams[IS_DEV], $config, $eventManager);
