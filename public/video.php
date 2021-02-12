<?php
require_once('../resources/autoload.php');

$videoSlug   = getGetRequestVar('video');
$videoObject = $videoRepository->findBySlug($videoSlug);
throw404OnEmpty($videoObject);

// on a POST ajax request, try to add a comment
if (isRequestAjax() && isRequest('POST')) {
    $response = processSaveCommentRequest($videoObject);
    rederJSONContent($response);
    exit;
}

// if the request is *NOT* an add-comment ajax request,
// add +1 to the views count of the video and fetch comments
$videoObject->incrementViews();
$entityManager->flush();

$commentUrl  = Url::generateVideoUrl($videoSlug);
$comments    = $commentRepository->getAllCommentsForEntity($videoObject);
$video       = $videoObject->getVideoDetails();
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
    'commentUrl'=> $commentUrl ?? NULL,
    'comments'  => $comments ?? NULL,
];

// mark the current video in the navigation
setCurrentNavPage(basename(__FILE__), $video['slug']);

renderLayoutWithContentFile('video.php', $vars);