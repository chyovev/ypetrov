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
$smarty->assign($vars);

renderLayoutWithContentFile('gallery.tpl');
