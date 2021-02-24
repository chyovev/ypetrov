<?php
require_once('../resources/autoload.php');
$code   = new Captcha(5);

$return = ['image' => $code->getImage()];
renderJSONContent($return);
