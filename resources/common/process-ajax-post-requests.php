<?php

use Doctrine\Common\Util\ClassUtils;
use Exceptions\ValidationException;
use Interfaces\Commentable;

// initiate the comment repository to use it when fetching comments
$commentRepository = $entityManager->getRepository('Comment');


///////////////////////////////////////////////////////////////////////////
function processSaveCommentRequest($object): array {
    global $entityManager, $commentRepository;

    try {
        $comment = $commentRepository->prepareCommentFromPostRequest($object);
        $entityManager->persist($comment);
        $entityManager->flush();

        return [
            'status'      => true,
            'type'        => 'comment',
            'html'        => renderContentWithNoLayout('elements/single-comment.tpl',       ['comment' => $comment->getDetails()]),
            'success_msg' => renderContentWithNoLayout('elements/form-success-message.tpl', ['type'    => 'comment']),
        ];
    }
    catch (ValidationException $e) {
        return [
            'status' => false,
            'errors' => $e->getErrors(),
        ];
    }

    // if some sort of an internal error occurs,
    // log it and use 500 http code
    catch (Exception $e) {
        http_response_code(500);
        Logger::logError($e);
    }
}

///////////////////////////////////////////////////////////////////////////
function processContactMessageRequest(): array {
    global $entityManager;

    try {
        $contactMessageRepository = $entityManager->getRepository('ContactMessage');
        
        $message = $contactMessageRepository->prepareMessageFromPostRequest();
        $entityManager->persist($message);
        $entityManager->flush();

        return [
            'status'      => true,
            'type'        => 'contact_message',
            'success_msg' => renderContentWithNoLayout('elements/form-success-message.tpl', ['type' => 'contact']),
        ];
    }
    catch (ValidationException $e) {
        return [
            'status' => false,
            'errors' => $e->getErrors(),
        ];
    }

    // if some sort of an internal error occurs,
    // log it and use 500 http code
    catch (Exception $e) {
        http_response_code(500);
        Logger::logError($e);
    }
}