<?php
require_once('../resources/autoload.php');

$bookSlug   = getGetRequestVar('slug');
$poemSlug   = getGetRequestVar('poem');

$bookEntity = $bookRepository->findBySlug($bookSlug);
$book       = $bookEntity->getDetails(true);

// proceed only if there's a book entity AND a content record with the poem slug
throw404OnEmpty($bookEntity && isset($book['contents'][$poemSlug]));

// poem is already pre-fetched through book contents association
$poemEntity = $book['contents'][$poemSlug]->getPoem();

// if there's a POST ajax request,
// process it as an attempt to add a comment
if (isRequestAjax() && isRequest('POST')) {
    $response = processSaveCommentRequest($poemEntity);
    renderJSONContent($response);
    exit;
}

// add +1 to the read count of the poem
$poemEntity->incrementReadCount();
$entityManager->flush();

$commentUrl = Url::generatePoemUrl($bookSlug, $poemSlug);
$comments   = $commentRepository->getAllCommentsForEntity($poemEntity);

$poem       = $poemEntity->getDetails();
$metaTitle  = $poem['title'];
$metaDesc   = $poem['body'];

$code       = new Captcha();
$smarty->assign('captchaImg', $code->getImage());

// for regular GET requests render complete page
if ( ! isRequestAjax()) {
    $metaImage  = [
        'url'  => $book['image'],
        'size' => getImageDimensions($book['image']),
    ];

    $canonicalUrl = isset($poemEntity) ? (getPoemCanonicalUrl($poemEntity, $poemSlug) ?? Url::generatePoemUrl($bookSlug, $poemSlug)) : NULL;

    $vars = [
        'book'      => $book,
        'metaTitle' => $metaTitle,
        'metaDesc'  => $metaDesc,
        'metaImage' => $metaImage,
        'poem'      => $poem,
        'commentUrl'=> $commentUrl,
        'comments'  => $comments ?? NULL,
        'canonical' => HOST_URL . $canonicalUrl,
    ];
    $smarty->assign($vars);

    renderLayoutWithContentFile('poem.tpl');
    exit;
}

// for AJAX requests send JSON response containing only what's needed
else {
    // if there's a poem, load its content
    $commentsHTML = renderContentWithNoLayout('elements/comment-section.tpl', ['commentUrl' => $commentUrl, 'comments' => $comments]);
    
    $response = [
        'metaTitle'  => escape($metaTitle . META_SUFFIX),
        'title'      => $poem['title'],
        'dedication' => nl2br($poem['dedication']),
        'body'       => $poem['body'],
        'monospace'  => (bool) $poem['use_monospace_font'],
        'comments'   => $commentsHTML,
    ];

    renderJSONContent($response);
    exit;
}


///////////////////////////////////////////////////////////////////////////////
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