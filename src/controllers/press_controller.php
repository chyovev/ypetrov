<?php
class PressController extends CommentableController {

    ///////////////////////////////////////////////////////////////////////////
    public function view() {
        $slug   = Router::getRequestParam('press');
        $entity = $this->PressArticle->findBySlug($slug);

        $this->_throw404OnEmpty($entity);

        // try to save an ajax post request comment
        $this->saveComment($entity);

        $this->incrementReadCount($entity);

        $article  = $entity->getDetails();
        $comments = $this->loadComments($entity);
        $subtitle = implode(', ', array_filter([$article['press'], beautifyDate('%d.%m.%Y г.', $article['published_date'])]));

        $vars = [
            'title'      => $article['title'],
            '_slug'      => $slug,
            'subtitle'   => $subtitle,
            'metaTitle'  => $article['title'],
            'body'       => $article['body'],
            'metaDesc'   => $subtitle ?? $article['body'],
            'comments'   => $comments,
        ];

        $this->smarty->showPage('textpage.tpl', $vars);

    }

}