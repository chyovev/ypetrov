<?php
require_once('../resources/autoload.php');

$bookSlug   = getGetRequestVar('book');
$poemSlug   = getGetRequestVar('poem');

$bookObject = $bookRepository->findBySlug($bookSlug);
throw404OnEmpty($bookObject);

$book       = $bookObject->getDetails(true);
$metaTitle  = $book['title'] . ' (' . $book['published_year'] . ')';
$metaDesc   = sprintf('Година на издаване: %s г.; Стихотворения: %s', $book['published_year'], count($book['contents']));

// if there's a poem slug, make sure it exists as a book contents key
if (isset($poemSlug)) {

    throw404OnEmpty(isset($book['contents'][$poemSlug]));

    // poem is already pre-fetched through book contents association
    $poemObject = $book['contents'][$poemSlug]->getPoem();

    // if there's a POST ajax request,
    // process it as an attempt to add a comment
    if (isRequestAjax() && isRequest('POST')) {
        $response = processSaveCommentRequest($poemObject);
        rederJSONContent($response);
        exit;
    }

    // otherwise, continue loading the poem
    $poem       = $poemObject->getDetails();

    // add +1 to the read count of the poem
    $poemObject->incrementReadCount();
    $entityManager->flush();

    $commentUrl = Url::generatePoemUrl($bookSlug, $poemSlug);
    $comments   = $commentRepository->getAllCommentsForEntity($poemObject);

    // prepend meta title with poem
    $metaTitle  = $poem['title'];
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

    $canonicalUrl = isset($poemObject) ? (getPoemCanonicalUrl($poemObject, $poemSlug) ?? Url::generatePoemUrl($bookSlug, $poemSlug)) : NULL;

    $vars = [
        'book'      => $book,
        'metaTitle' => $metaTitle,
        'metaDesc'  => $metaDesc ?? NULL,
        'metaImage' => $metaImage,
        'poem'      => $poem ?? NULL,
        'commentUrl'=> $commentUrl ?? NULL,
        'comments'  => $comments ?? NULL,
        'canonical' => $canonicalUrl ? (HOST_URL . $canonicalUrl) : NULL,
    ];

    renderLayoutWithContentFile('poem.php', $vars);
    exit;
}

// for AJAX requests send JSON response containing only what's needed
else {
    // if there's a poem, load its content
    if (isset($poem)) {
        $commentsHTML = renderContentWithNoLayout('elements/comment-section.php', ['commentUrl' => $commentUrl, 'comments' => $comments]);
        
        $response = [
            'metaTitle'  => escape($metaTitle . META_SUFFIX),
            'title'      => $poem['title'],
            'dedication' => nl2br($poem['dedication']),
            'body'       => $poem['body'],
            'monospace'  => (bool) $poem['use_monospace_font'],
            'comments'   => $commentsHTML,
        ];
    }

    // otherwise load book details
    else {
        $bookHTML = renderContentWithNoLayout('elements/book-details.php', ['book' => $book]);
        
        $response = [
            'metaTitle'  => escape($metaTitle . META_SUFFIX),
            'title'      => $book['title'],
            'dedication' => NULL,
            'body'       => $bookHTML,
            'monospace'  => false,
            'comments'   => NULL,
        ];
    }

    rederJSONContent($response);
    exit;
}


function getPoemCanonicalUrl(Poem $poemEntity, string $poemSlug): ?string {
    // check how many books this poem is listed in
    $poemContent = $poemEntity->getContentsAsArray();

    // if it's just one, it's the current one: generate current url
    if ($poemContent == 1) {
        return NULL;
    }

    // otherwise cycle through all books and return the first active one
    foreach ($poemContent as $bookAssociation) {
        $bookEntity = $bookAssociation->getBook();
        $book       = $bookEntity->getDetails();

        if ($book['active']) {
            return Url::generatePoemUrl($book['slug'], $poemSlug);
        }
    }

    return NULL;
}