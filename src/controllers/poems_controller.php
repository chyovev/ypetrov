<?php
class PoemsController extends CommentableController {

    ///////////////////////////////////////////////////////////////////////////
    public function view() {
        $bookSlug   = Router::getRequestParam('book');
        $poemSlug   = Router::getRequestParam('poem');

        // instead of loading poem and book’s content separately,
        // load the book with its content and check if poem slug
        // is part of it
        $bookEntity = $this->Book->findBySlug($bookSlug);
        $this->_throw404OnEmpty($bookEntity);

        $book       = $bookEntity->getDetails(true);
        $poemEntity = $this->getPoemEntity($book, $poemSlug);

        // try to save an ajax post request comment
        $this->saveComment($poemEntity);

        $this->incrementReadCount($poemEntity);

        // regular request renders page, ajax request returns JSON
        $this->processRegularRequest($poemEntity, $book);
        $this->processAjaxRequest($poemEntity, $bookEntity);
    }

    ///////////////////////////////////////////////////////////////////////////
    // if poem slug is missing in book contents, throw an error
    protected function getPoemEntity(array $book, string $poemSlug): Poem {
        $this->_throw404OnEmpty(isset($book['contents'][$poemSlug]));

        return $book['contents'][$poemSlug]->getPoem();
    }

    ///////////////////////////////////////////////////////////////////////////
    protected function processRegularRequest(Poem $poemEntity, array $book) {
        if (Router::isRequest('AJAX')) {
            return;
        }

        $poem         = $poemEntity->getDetails();
        $comments     = $this->loadComments($poemEntity);
        
        $metaTitle    = $poem['title'];
        $metaDesc     = $poem['body'];
        $metaImage    = $this->getMetaImageData($book['image']);
        
        $canonicalUrl = $this->getPoemCanonicalUrl($poemEntity, $book['slug']);

        $vars = [
            'book'      => $book,
            '_slug'     => $book['slug'], // used for marking books in header as active
            'metaTitle' => $metaTitle,
            'metaDesc'  => $metaDesc,
            'metaImage' => $metaImage,
            'poem'      => $poem,
            'comments'  => $comments,
            'canonical' => $canonicalUrl,
        ];

        $this->smarty->showPage('poem.tpl', $vars);
    }

    ///////////////////////////////////////////////////////////////////////////
    protected function processAjaxRequest(Poem $entity) {
        if ( ! Router::isRequest('AJAX')) {
            return;
        }

        $poem         = $entity->getDetails();
        $comments     = $this->loadComments($entity);
        $commentsHTML = $this->generateCommentsHtml($comments);
        
        $metaTitle    = $poem['title'];
        
        $response = [
            'metaTitle'  => escape($metaTitle . META_SUFFIX),
            'title'      => $poem['title'],
            'dedication' => nl2br($poem['dedication']),
            'body'       => $poem['body'],
            'monospace'  => (bool) $poem['use_monospace_font'],
            'comments'   => $commentsHTML,
        ];

        $this->smarty->renderJSONContent($response);
    }

    ///////////////////////////////////////////////////////////////////////////////
    protected function getPoemCanonicalUrl(Poem $poem, string $currentBookSlug): string {
        // check how many books this poem is listed in
        $poemBooks = $poem->getContentsAsArray();

        // if it's just one, it's the current one: generate current url
        if (count($poemBooks) <= 1) {
            $url = Router::url(['controller' => 'poems', 'action' => 'view', 'book' => $currentBookSlug, 'poem' => $poem->getSlug()]);
        }

        // otherwise cycle through all books and return the first active one
        foreach ($poemBooks as $bookAssoc) {
            $book = $bookAssoc->getBook();

            if ($book->getActive()) {
                $url = Router::url(['controller' => 'poems', 'action' => 'view', 'book' => $book->getSlug(), 'poem' => $poem->getSlug()]);
                break;
            }
        }

        return HOST_URL . $url;
    }

}