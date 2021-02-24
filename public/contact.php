<?php
require_once('../resources/autoload.php');

if (isRequestAjax() && isRequest('POST')) {
    $response = processContactMessageRequest();
    renderJSONContent($response);
    exit;
}

// generate captcha code on GET request
$code = new Captcha();

$vars = [
    'metaTitle'  => 'Контакт',
    'captchaImg' => $code->getImage(),
];
$smarty->assign($vars);

renderLayoutWithContentFile('contact.tpl');
