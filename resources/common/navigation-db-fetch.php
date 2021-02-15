<?php
// some database records (such as book titles) are needed on all pages
// such information gets loaded here
$bookRepository  = $entityManager->getRepository('Book');
$allBooks        = $bookRepository->getAllActiveBooks();

$videoRepository = $entityManager->getRepository('Video');
$allVideos       = $videoRepository->getAllActiveVideos();

$pressRepository = $entityManager->getRepository('PressArticle');
$allArticles     = $pressRepository->getAllActiveArticles();

// this variable is global for the renderLayoutWithContentFile() function
// so it can be used in the header.php layout
$navigation = [
    'articles' => $allArticles,
    'books'    => $allBooks,
    'videos'   => $allVideos,
];

setGlobalNavigation($navigation);
