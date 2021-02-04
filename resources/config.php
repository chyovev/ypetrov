<?php
define('TEMPLATES_PATH', realpath(dirname(__FILE__) . '/templates'));
define('LAYOUTS_PATH',   TEMPLATES_PATH . '/layout');
define('COMMON_PATH',    realpath(dirname(__FILE__) . '/common'));

define('WEBROOT',        preg_replace('/\/resources/', '', substr(dirname(__FILE__), strlen($_SERVER['DOCUMENT_ROOT'])) . '/'));

ini_set('error_reporting', 'true');
error_reporting(E_ALL|E_STRCT);

// set locale to bulgarian for dates and times
setlocale(LC_TIME, "bg_BG.UTF-8", "bg_BG.UTF-8@euro", "bul_bul.UTF-8", "bul.UTF-8", "bgr_BGR.UTF-8", "bgr.UTF-8", "bulgarian.UTF-8");