<?php

use Interfaces\Commentable;
use Exceptions\ValidationException;

abstract class CommentableController extends AppController {

    ///////////////////////////////////////////////////////////////////////////
    protected function loadComments($entity) {
        $isEntityCommentable = ($entity instanceof Commentable);
        if ( ! $isEntityCommentable) {
            return NULL;
        }

        // captcha can also be loaded here as it will be used in the form
        $this->loadCaptcha();

        // Comment model is already loaded in the AppController's $globalModels
        return $this->Comment->getAllCommentsForEntity($entity) ?? [];
    }

    
    ///////////////////////////////////////////////////////////////////////////
    protected function generateCommentsHtml($comments): string {
        return $this->smarty->loadTemplate('elements/comment-section.tpl', ['comments' => $comments]);
    }

    ///////////////////////////////////////////////////////////////////////////
    protected function saveComment($entity): void {
        $isEntityCommentable = ($entity instanceof Commentable);
        $isRequestAjax       = Router::isRequest('AJAX');
        $isRequestPost       = Router::isRequest('POST');

        // if the request is not POST and AJAX or the entity is not commentable,
        // don't do anything
        if ( ! ($isEntityCommentable && $isRequestAjax && $isRequestPost)) {
            return;
        }

        // try to save the comment,
        // if it succeeds, generate comment HTML
        try {
            $entityManager = $this->getEntityManager();

            // Comment model is already loaded in the AppController's $globalModels
            $comment = $this->Comment->prepareCommentFromPostRequest($entity);
            $entityManager->persist($comment);
            $entityManager->flush();

            $response = [
                'status'      => true,
                'type'        => 'comment',
                'html'        => $this->smarty->loadTemplate('elements/single-comment.tpl',       ['comment' => $comment->getDetails()]),
                'success_msg' => $this->smarty->loadTemplate('elements/form-success-message.tpl', ['type'    => 'comment']),
            ];
        }

        // if comment save fails due to validation, return the errors
        catch (ValidationException $e) {
            $response = [
                'status' => false,
                'errors' => $e->getErrors(),
            ];
        }

        // if it fails for some other reason, log it and show a general error
        catch (Exception $e) {
            Logger::logError($e);
            $response = ['status' => false];
        }

        $this->smarty->renderJSONContent($response);
    }

}