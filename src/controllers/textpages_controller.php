<?php
class TextpagesController extends CommentableController {

    public $models = ['TextPage'];

    ///////////////////////////////////////////////////////////////////////////
    public function view() {
        $slug   = Router::getRequestParam('textpage');
        $entity = $this->TextPage->findBySlug($slug);

        $this->_throw404OnEmpty($entity);

        // try to save an ajax post request comment
        $this->saveComment($entity);

        $this->incrementReadCount($entity);

        $page     = $entity->getDetails();
        $comments = $this->loadComments($entity);

        $vars = [
            'title'     => $page['title'],
            '_slug'     => $slug,
            'metaTitle' => $page['title'],
            'body'      => $page['body'],
            'metaDesc'  => $page['body'],
            'comments'  => $comments,
        ];

        $this->smarty->showPage('textpage.tpl', $vars);

    }

}