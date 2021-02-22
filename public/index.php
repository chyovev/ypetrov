<?php
require_once('../resources/autoload.php');

$pageRepository = $entityManager->getRepository('TextPage');
$entity         = $pageRepository->findOneBy(['slug' => 'home']);
$page           = $entity->getDetails();

// add +1 to the read count of the page and fetch comments
$entity->incrementReadCount();
$entityManager->flush();

$vars = [
    'title' => $page['title'],
    'body'  => $page['body'],
    'nohr'  => true,
];
$smarty->assign($vars);

renderLayoutWithContentFile('textpage.tpl');