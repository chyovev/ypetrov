<?php
require_once('../resources/autoload.php');

$videoSlug   = $_GET['video'] ?? NULL;
$videoObject = $videoRepository->findBySlug($videoSlug);
throw404OnEmpty($videoObject);

// add +1 to the views count of the video
$videoObject->incrementViews();
$entityManager->flush();

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
];

// mark the current video in the navigation
setCurrentNavPage(basename(__FILE__), $video['slug']);

renderLayoutWithContentFile('video.php', $vars);