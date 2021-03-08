<?php
class EssaysController extends CommentableController {

    ///////////////////////////////////////////////////////////////////////////
    public function view() {
        $slug   = Router::getRequestParam('essay');
        $entity = $this->Essay->findBySlug($slug);

        $this->_throw404OnEmpty($entity);

        // try to save an ajax post request comment
        $this->saveComment($entity);

        $this->incrementReadCount($entity);

        $essay    = $entity->getDetails();
        $comments = $this->loadComments($entity);

        $vars = [
            'title'      => $essay['title'],
            '_slug'      => $slug,
            'metaTitle'  => $essay['title'],
            'body'       => $essay['body'],
            'metaDesc'   => $essay['body'],
            'comments'   => $comments,
        ];

        $this->smarty->showPage('textpage.tpl', $vars);

    }

}