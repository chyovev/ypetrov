<?php
require_once('../resources/autoload.php');

$videoSlug   = getGetRequestVar('slug');
$videoEntity = $videoRepository->findBySlug($videoSlug);
throw404OnEmpty($videoEntity);

// on a POST ajax request, try to add a comment
if (isRequestAjax() && isRequest('POST')) {
    $response = processSaveCommentRequest($videoEntity);
    renderJSONContent($response);
    exit;
}

// if the request is *NOT* an add-comment ajax request,
// add +1 to the views count of the video and fetch comments
$videoEntity->incrementViews();
$entityManager->flush();

$comments    = $commentRepository->getAllCommentsForEntity($videoEntity);
$video       = $videoEntity->getDetails();
$metaTitle   = $video['title'];
$metaDesc    = $video['summary'];
$metaImage   = [
    'url'  => $video['video']['jpg'],
    'size' => getImageDimensions($video['video']['jpg']),
];

$vars = [
    'mainVideo' => $video,
    'metaTitle' => $metaTitle,
    'metaDesc'  => $metaDesc,
    'metaImage' => $metaImage,
    'comments'  => $comments,
];
$smarty->assign($vars);

renderLayoutWithContentFile('video.tpl');