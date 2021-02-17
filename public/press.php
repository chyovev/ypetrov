<?php
require_once('../resources/autoload.php');

$slug   = getGetRequestVar('article');
$entity = $pressRepository->findBySlug($slug);
throw404OnEmpty($entity);

// on a POST ajax request, try to add a comment
if (isRequestAjax() && isRequest('POST')) {
    $response = processSaveCommentRequest($entity);
    renderJSONContent($response);
    exit;
}

// add +1 to the read count of the article and fetch comments
$entity->incrementReadCount();
$entityManager->flush();

$commentUrl = Url::generatePressUrl($slug);
$comments   = $commentRepository->getAllCommentsForEntity($entity);
$article    = $entity->getDetails();
$subtitle   = implode(', ', array_filter([$article['press'], beautifyDate('%d.%m.%Y г.', $article['published_date'])]));

$vars = [
    'title'      => $article['title'],
    'subtitle'   => $subtitle,
    'metaTitle'  => $article['title'],
    'body'       => $article['body'],
    'metaDesc'   => $subtitle ?? $article['body'],
    'commentUrl' => $commentUrl,
    'comments'   => $comments,
];

// mark the current article in the navigation
setCurrentNavPage(basename(__FILE__), $slug);

renderLayoutWithContentFile('textpage.php', $vars);