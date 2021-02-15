<?php
require_once('../resources/autoload.php');

$articleSlug   = getGetRequestVar('article');
$articleObject = $pressRepository->findBySlug($articleSlug);
throw404OnEmpty($articleObject);

// on a POST ajax request, try to add a comment
if (isRequestAjax() && isRequest('POST')) {
    $response = processSaveCommentRequest($articleObject);
    rederJSONContent($response);
    exit;
}

// add +1 to the read count of the article and fetch comments
$articleObject->incrementReadCount();
$entityManager->flush();

$commentUrl  = Url::generatePressUrl($articleSlug);
$comments    = $commentRepository->getAllCommentsForEntity($articleObject);
$article     = $articleObject->getArticleDetails();
$metaTitle   = $article['title'];
$metaDesc    = $article['body'];

$vars = [
    'article'    => $article,
    'metaTitle'  => $metaTitle,
    'metaDesc'   => $metaDesc,
    'commentUrl' => $commentUrl,
    'comments'   => $comments,
];

// mark the current article in the navigation
setCurrentNavPage(basename(__FILE__), $article['slug']);

renderLayoutWithContentFile('press.php', $vars);