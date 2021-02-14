<?php
// autoload.php gets included in every php file located in the /public folder.
// The file itself loads all other necessary files, config.php being the first of them.

require_once('config.php');

require_once(COMMON_PATH   . '/functions.php');
require_once(DOCTRINE_PATH . '/bootstrap.php');
require_once(COMMON_PATH   . '/Url.php');
require_once(COMMON_PATH   . '/navigation-db-fetch.php');
require_once(COMMON_PATH   . '/process-ajax-post-requests.php');
require_once(COMMON_PATH   . '/Logger.php');
