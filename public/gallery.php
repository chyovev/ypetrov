<?php
require_once('../resources/autoload.php');

$galleryRepository = $entityManager->getRepository('Gallery');
$allGalleryImages  = $galleryRepository->getAllActiveGalleryImages();

$metaTitle = 'Галерия';
$metaDesc  = 'Снимки от живота на Йосиф Петров';

$vars = [
    'metaTitle' => $metaTitle,
    'metaDesc'  => $metaDesc,
    'images'    => $allGalleryImages,
];

// mark the current video in the navigation
setCurrentNavPage(basename(__FILE__));

renderLayoutWithContentFile('gallery.php', $vars);
