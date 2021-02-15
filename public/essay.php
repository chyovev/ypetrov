<?php
require_once('../resources/autoload.php');

$slug   = getGetRequestVar('essay');
$entity = $essayRepository->findBySlug($slug);
throw404OnEmpty($entity);

// on a POST ajax request, try to add a comment
if (isRequestAjax() && isRequest('POST')) {
    $response = processSaveCommentRequest($entity);
    rederJSONContent($response);
    exit;
}

// add +1 to the read count of the essay and fetch comments
$entity->incrementReadCount();
$entityManager->flush();

$commentUrl = Url::generateEssayUrl($slug);
$comments   = $commentRepository->getAllCommentsForEntity($entity);
$essay    = $entity->getDetails();

$vars = [
    'title'      => $essay['title'],
    'metaTitle'  => $essay['title'],
    'body'       => $essay['body'],
    'metaDesc'   => $essay['body'],
    'commentUrl' => $commentUrl,
    'comments'   => $comments,
];

// mark the current essay in the navigation
setCurrentNavPage(basename(__FILE__), $slug);

renderLayoutWithContentFile('textpage.php', $vars);