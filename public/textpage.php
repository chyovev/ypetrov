<?php
require_once('../resources/autoload.php');

$slug           = getGetRequestVar('slug');
$pageRepository = $entityManager->getRepository('TextPage');
$entity         = $pageRepository->findBySlug($slug);
throw404OnEmpty($entity);

// on a POST ajax request, try to add a comment
if (isRequestAjax() && isRequest('POST')) {
    $response = processSaveCommentRequest($entity);
    renderJSONContent($response);
    exit;
}

// add +1 to the read count of the page and fetch comments
$entity->incrementReadCount();
$entityManager->flush();

$commentUrl = Url::generateTextPageUrl($slug);
$comments   = $commentRepository->getAllCommentsForEntity($entity);
$page       = $entity->getDetails();

$vars = [
    'title'      => $page['title'],
    'metaTitle'  => $page['title'],
    'body'       => $page['body'],
    'metaDesc'   => $page['body'],
    'commentUrl' => $commentUrl,
    'comments'   => $comments,
];
$smarty->assign($vars);

// mark the current page in the navigation
setCurrentNavPage(basename(__FILE__), $slug);

renderLayoutWithContentFile('textpage.tpl');