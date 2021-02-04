<?php
require_once('../resources/autoload.php');
header("HTTP/1.1 404 Not Found"); 
renderLayoutWithContentFile('error404.php');