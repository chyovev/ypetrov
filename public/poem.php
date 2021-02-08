<?php
require_once('../resources/autoload.php');

$bookSlug   = $_GET['book'] ?? NULL;
$poemSlug   = $_GET['poem'] ?? NULL;

$bookObject = $bookRepository->findBySlug($bookSlug);
throw404OnEmpty($bookObject);

$book       = $bookObject->getBookDetails(true);
$metaTitle  = $book['title'] . ' (' . $book['published_year'] . ')';
$metaDesc   = sprintf('Година на издаване: %s г.; Стихотворения: %s', $book['published_year'], count($book['contents']));

// if there's a poem slug, make sure it exists as a book contents key
if (isset($poemSlug)) {

    throw404OnEmpty(isset($book['contents'][$poemSlug]));

    // poem is already pre-fetched through book contents association
    $poemObject = $book['contents'][$poemSlug]->getPoem();
    $poem       = $poemObject->getPoemDetails();

    // add +1 to the read count of the poem
    $poemObject->incrementReadCount();
    $entityManager->flush();

    // prepend meta title with poem
    $metaTitle  = $poem['title'] . ' | ' . $metaTitle;
    $metaDesc   = $poem['body'];
}

// for regular GET requests render complete page
if ( ! isRequestAjax()) {
    // mark the current book in the navigation
    setCurrentNavPage(basename(__FILE__), $book['slug']);

    $metaImage  = [
        'url'  => $book['image'],
        'size' => getImageDimensions($book['image']),
    ];

    $vars = [
        'book'      => $book,
        'metaTitle' => $metaTitle,
        'metaDesc'  => $metaDesc ?? NULL,
        'metaImage' => $metaImage,
        'poem'      => $poem ?? NULL,
    ];

    renderLayoutWithContentFile('poem.php', $vars);
}

// for AJAX requests send JSON response containing only what's needed
else {
    // if there's no $poem object, load the book information instead
    $response = [
        'metaTitle'  => escape($metaTitle . META_SUFFIX),
        'title'      => $poem['title']      ?? $book['title'],
        'dedication' => $poem['dedication'] ?? NULL,
        'body'       => $poem['body']       ?? renderContentWithNoLayout('book-details.php', ['book' => $book]),
        'monospace'  => (bool) ($poem['use_monospace_font'] ?? false),
    ];

    rederJSONContent($response);
}