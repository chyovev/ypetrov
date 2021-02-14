<?php
require_once('../resources/autoload.php');

use Exceptions\ValidationException;

if (isRequestAjax() && isRequest('POST')) {
    $response = processContactMessageRequest();
    rederJSONContent($response);
    exit;
}

$metaTitle = 'Контакт';
$vars      = ['metaTitle' => $metaTitle];

setCurrentNavPage(basename(__FILE__), NULL);

renderLayoutWithContentFile('contact.php', $vars);
