<?php
require_once('../resources/autoload.php');

$slug   = getGetRequestVar('slug');
$entity = $essayRepository->findBySlug($slug);
throw404OnEmpty($entity);

// on a POST ajax request, try to add a comment
if (isRequestAjax() && isRequest('POST')) {
    $response = processSaveCommentRequest($entity);
    renderJSONContent($response);
    exit;
}

// add +1 to the read count of the essay and fetch comments
$entity->incrementReadCount();
$entityManager->flush();

$comments   = $commentRepository->getAllCommentsForEntity($entity);
$essay      = $entity->getDetails();

$vars = [
    'title'      => $essay['title'],
    'metaTitle'  => $essay['title'],
    'body'       => $essay['body'],
    'metaDesc'   => $essay['body'],
    'comments'   => $comments,
];

$smarty->assign($vars);

renderLayoutWithContentFile('textpage.tpl');