<?php
define('DOMAIN',         'yosifpetrov.com');
define('IS_DEV',         (bool) ! preg_match('/'.DOMAIN.'/', $_SERVER['HTTP_HOST']));
define('META_SUFFIX',    ' | Йосиф Петров');

define('TEMPLATES_PATH', realpath(dirname(__FILE__) . '/templates'));
define('LAYOUTS_PATH',   realpath(dirname(__FILE__) . '/templates/layout'));
define('COMMON_PATH',    realpath(dirname(__FILE__) . '/common'));
define('VENDOR_PATH',    realpath(dirname(__FILE__) . '/../vendor'));
define('DOCTRINE_PATH',  realpath(dirname(__FILE__) . '/doctrine')); // this is not the vendor itself, but the doctrine code for the project

define('ROOT',           realpath(dirname(__FILE__).'/../'));
define('WEBROOT',        preg_replace('/\/resources/', '', substr(dirname(__FILE__), strlen($_SERVER['DOCUMENT_ROOT'])) . '/'));
define('HOST_URL',       $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST']);
define('WEBROOT_FULL',   HOST_URL . WEBROOT);
define('IMG_CONTENT',    WEBROOT . 'resources/img/content');
define('IMG_LAYOUT',     WEBROOT . 'resources/img/layout');
define('VIDEO_PATH',     WEBROOT . 'resources/videos');

ini_set('error_reporting', 'true');
error_reporting(E_ALL|E_STRCT);

// set locale to bulgarian for dates and times
setlocale(LC_TIME, "bg_BG.UTF-8", "bg_BG.UTF-8@euro", "bul_bul.UTF-8", "bul.UTF-8", "bgr_BGR.UTF-8", "bgr.UTF-8", "bulgarian.UTF-8");