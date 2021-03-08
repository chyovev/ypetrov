<?php
class VideosController extends CommentableController {

    ///////////////////////////////////////////////////////////////////////////
    public function view() {
        $slug   = Router::getRequestParam('video');
        $entity = $this->Video->findBySlug($slug);

        $this->_throw404OnEmpty($entity);

        // try to save an ajax post request comment
        $this->saveComment($entity);

        $this->incrementViews($entity);

        $video     = $entity->getDetails();
        $comments  = $this->loadComments($entity);
        
        $metaTitle = $video['title'];
        $metaDesc  = $video['summary'];
        $metaImage = $this->getMetaImageData($video['video']['jpg']);

        $vars = [
            'mainVideo' => $video,
            '_slug'     => $slug,
            'metaTitle' => $metaTitle,
            'metaDesc'  => $metaDesc,
            'metaImage' => $metaImage,
            'comments'  => $comments,
        ];

        $this->smarty->showPage('video.tpl', $vars);
    }

    ///////////////////////////////////////////////////////////////////////////
    // add +1 to the views count of the video and fetch comments
    protected function incrementViews(Video $entity): void {
        $entity->incrementViews();
        $this->getEntityManager()->flush();
    }

}