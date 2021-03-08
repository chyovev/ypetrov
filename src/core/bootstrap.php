<?php
// bootstrap.php gets included in the public/index.php file
// where all requests are sent to.
//
// bootstrap.php calls two files:
//
//     1) config.php
//     2) vendor/autoload.php
//
// The first one contains constant declarations and DB/email parameters.
// autoload.php on the other hand is set up to load all vendor-related files,
// but also other custom classes and files (see composer.json autoload declaration).

session_start();

require_once('constants.php');

require_once(VENDOR_PATH   . '/autoload.php');