<?php

use Doctrine\Common\EventManager;
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use PHPMailer\PHPMailer\PHPMailer;

abstract class Initializer {

    ///////////////////////////////////////////////////////////////////////////
    public static function doctrine(): EntityManager {
        $params         = DatabaseConfig::getParams();
        $entitiesFolder = [DOCTRINE_PATH . '/xml'];
        $proxiesFolder  =  DOCTRINE_PATH . '/proxies';

        // register comment event subscriber for validation and mail notifications
        $eventManager   = new EventManager();
        $eventManager->addEventSubscriber(new CommentSubscriber());
        $eventManager->addEventSubscriber(new ContactMessageSubscriber());

        $config         = Setup::createXMLMetadataConfiguration($entitiesFolder, IS_PROD, $proxiesFolder);
        $entityManager  = EntityManager::create($params, $config, $eventManager);

        return $entityManager;
    }

    ///////////////////////////////////////////////////////////////////////////
    public static function smarty() {
        $smarty = new ExtendedSmarty();

        $smarty->setCacheDir(SMARTY_PATH    . '/cache')
               ->setCompileDir(SMARTY_PATH  . '/compile')
               ->setConfigDir(SMARTY_PATH   . '/configs')
               ->addPluginsDir(SMARTY_PATH  . '/plugins')
               ->setTemplateDir(SMARTY_PATH . '/../../views');

       return $smarty;
    }

    ///////////////////////////////////////////////////////////////////////////
    public static function PHPMailer(bool $throwExceptions = false) {
        return new PHPMailer($throwExceptions);
    }
}