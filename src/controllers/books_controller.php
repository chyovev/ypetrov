<?php
class BooksController extends CommentableController {

    ///////////////////////////////////////////////////////////////////////////
    public function view() {
        $slug   = Router::getRequestParam('book');
        $entity = $this->Book->findBySlug($slug);

        $this->_throw404OnEmpty($entity);

        // try to save an ajax post request comment
        $this->saveComment($entity);

        // regular request renders page, ajax request returns JSON
        $this->processRegularRequest($entity);
        $this->processAjaxRequest($entity);
    }

    ///////////////////////////////////////////////////////////////////////////
    protected function processRegularRequest(Book $entity) {
        if (Router::isRequest('AJAX')) {
            return;
        }

        $book       = $entity->getDetails(true);
        $comments   = $this->loadComments($entity);

        $metaTitle  = $book['title'] . ' (' . $book['published_year'] . ')';
        $metaDesc   = sprintf('Година на издаване: %s г.; Стихотворения: %s', $book['published_year'], count($book['contents']));
        $metaImage  = $this->getMetaImageData($book['image']);

        $vars = [
            'book'      => $book,
            '_slug'     => $book['slug'], // used for marking books in header as active
            'metaTitle' => $metaTitle,
            'metaDesc'  => $metaDesc,
            'metaImage' => $metaImage,
            'comments'  => $comments,
        ];

        $this->smarty->showPage('poem.tpl', $vars);
    }

    ///////////////////////////////////////////////////////////////////////////
    protected function processAjaxRequest(Book $entity) {
        if ( ! Router::isRequest('AJAX')) {
            return;
        }

        $book      = $entity->getDetails(true);
        $comments  = $this->loadComments($entity);

        $metaTitle = $book['title'] . ' (' . $book['published_year'] . ')';

        $commentsHTML = $this->generateCommentsHtml($comments);
        $bookHTML     = $this->smarty->loadTemplate('elements/book-details.tpl',    ['book'     => $book]);
        
        $response = [
            'metaTitle'  => escape($metaTitle . META_SUFFIX),
            'title'      => $book['title'],
            'dedication' => NULL,
            'body'       => $bookHTML,
            'monospace'  => false,
            'comments'   => $commentsHTML,
        ];

        $this->smarty->renderJSONContent($response);
    }
}