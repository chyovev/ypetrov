<?php
// some database records (such as book titles) are needed on all pages
// such information gets loaded here
$bookRepository  = $entityManager->getRepository('Book');
$allBooks        = $bookRepository->getAllActiveBooks();

// this variable is global for the renderLayoutWithContentFile() function
// so it can be used in the header.php layout
$navigation = [
    'books'  => $allBooks,
];

setGlobalNavigation($navigation);
