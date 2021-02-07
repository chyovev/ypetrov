<?php
require_once('../resources/autoload.php');

$bookSlug   = $_GET['book'] ?? NULL;
$poemSlug   = $_GET['poem'] ?? NULL;

$bookObject = $bookRepository->findBySlug($bookSlug);
throw404OnEmpty($bookObject);

$book       = $bookObject->getBookDetails(true);
$metaTitle  = $book['title'] . ' (' . $book['published_year'] . ')';

// if there's a poem slug, make sure it exists as a book contents key
if (isset($poemSlug)) {

    throw404OnEmpty(isset($book['contents'][$poemSlug]));

    // poem is already pre-fetched through book contents association
    $poemObject = $book['contents'][$poemSlug]->getPoem();
    $poem       = $poemObject->getPoemDetails();

    // add +1 to the read count of the poem
    $poemObject->increaseReadCount();
    $entityManager->flush();

    // prepend meta title with poem
    $metaTitle  = $poem['title'] . ' | ' . $metaTitle;
}

// mark the current book in the navigation
setCurrentNavPage(basename(__FILE__), $book['slug']);

$vars = [
    'book'      => $book,
    'metaTitle' => $metaTitle,
    'poem'      => $poem ?? NULL,
];

renderLayoutWithContentFile('poem.php', $vars);