<?php
require_once('../resources/autoload.php');

if (isRequestAjax() && isRequest('POST')) {
    $response = processContactMessageRequest();
    renderJSONContent($response);
    exit;
}

$metaTitle = 'Контакт';
$vars      = ['metaTitle' => $metaTitle];

setCurrentNavPage(basename(__FILE__), NULL);

renderLayoutWithContentFile('contact.php', $vars);
