<?php
require_once('../resources/autoload.php');

$bookSlug   = getGetRequestVar('slug');
$bookEntity = $bookRepository->findBySlug($bookSlug);
throw404OnEmpty($bookEntity);

// if there's a POST ajax request,
// process it as an attempt to add a comment
if (isRequestAjax() && isRequest('POST')) {
    $response = processSaveCommentRequest($bookEntity);
    renderJSONContent($response);
    exit;
}

$book       = $bookEntity->getDetails(true);
$comments   = $commentRepository->getAllCommentsForEntity($bookEntity);
$metaTitle  = $book['title'] . ' (' . $book['published_year'] . ')';
$metaDesc   = sprintf('Година на издаване: %s г.; Стихотворения: %s', $book['published_year'], count($book['contents']));

$code       = new Captcha();
$smarty->assign('captchaImg', $code->getImage());


// for regular GET requests render complete page
if ( ! isRequestAjax()) {
    $metaImage  = [
        'url'  => $book['image'],
        'size' => getImageDimensions($book['image']),
    ];

    $vars = [
        'book'      => $book,
        'poem'      => NULL,
        'metaTitle' => $metaTitle,
        'metaDesc'  => $metaDesc,
        'metaImage' => $metaImage,
        'comments'  => $comments ?? NULL,
    ];
    $smarty->assign($vars);

    renderLayoutWithContentFile('poem.tpl');
    exit;
}

// for AJAX requests send JSON response containing only what's needed
else {
    $bookHTML     = renderContentWithNoLayout('elements/book-details.tpl',    ['book'     => $book]);
    $commentsHTML = renderContentWithNoLayout('elements/comment-section.tpl', ['comments' => $comments]);
    
    $response = [
        'metaTitle'  => escape($metaTitle . META_SUFFIX),
        'title'      => $book['title'],
        'dedication' => NULL,
        'body'       => $bookHTML,
        'monospace'  => false,
        'comments'   => $commentsHTML,
    ];

    renderJSONContent($response);
    exit;
}