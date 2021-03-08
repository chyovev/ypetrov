<?php
require_once('../src/core/bootstrap.php');

// turn on errors
ini_set('error_reporting', 'true');
error_reporting(E_ALL|E_STRCT);

// set locale to bulgarian for dates and times
setlocale(LC_TIME, "bg_BG.UTF-8", "bg_BG.UTF-8@euro", "bul_bul.UTF-8", "bul.UTF-8", "bgr_BGR.UTF-8", "bgr.UTF-8", "bulgarian.UTF-8");

// redirect current page if it's found in redirects.txt file
Redirector::checkCurrentPage();

// try to process current request
// if it doesn't work – load 404 on prod or show errors on dev
Router::processRequest();