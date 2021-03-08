<?php
define('DOMAIN',         'yosifpetrov.com');
define('IS_PROD',        (bool) preg_match('/'.DOMAIN.'/', $_SERVER['HTTP_HOST']));
define('META_SUFFIX',    ' | Йосиф Петров (1909 – 2004)');

define('CORE_PATH',      realpath(dirname(__FILE__)));
define('VENDOR_PATH',    realpath(dirname(__FILE__) . '/../../vendor'));
define('DOCTRINE_PATH',  realpath(dirname(__FILE__) . '/../models/doctrine')); // this is not the vendor itself, but the doctrine code for the project
define('SMARTY_PATH',    realpath(dirname(__FILE__) . '/smarty'));
define('CONTROLLER_PATH', realpath(dirname(__FILE__) . '/../controllers'));

define('ROOT',           realpath(dirname(__FILE__).'/../../'));
define('WEBROOT',        preg_replace('/\/src\/core/', '', substr(dirname(__FILE__), strlen($_SERVER['DOCUMENT_ROOT'])) . '/'));
define('HOST_URL',       $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST']);
define('WEBROOT_FULL',   HOST_URL . WEBROOT);
