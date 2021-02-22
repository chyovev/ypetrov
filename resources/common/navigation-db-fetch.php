<?php
// some database records (such as book titles) are needed on all pages
// such information gets loaded here
$bookRepository  = $entityManager->getRepository('Book');
$allBooks        = $bookRepository->findActive();

$videoRepository = $entityManager->getRepository('Video');
$allVideos       = $videoRepository->findActive();

$pressRepository = $entityManager->getRepository('PressArticle');
$allArticles     = $pressRepository->findActive();

$essayRepository = $entityManager->getRepository('Essay');
$allEssays       = $essayRepository->findActive();

$navigation = [
    'articles' => $allArticles,
    'books'    => $allBooks,
    'essays'   => $allEssays,
    'videos'   => $allVideos,
];

$smarty->assign(['navigation' => $navigation]);
