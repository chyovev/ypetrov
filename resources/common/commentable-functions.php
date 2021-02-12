<?php

use Doctrine\Common\Util\ClassUtils;
use Exceptions\ValidationException;
use Interfaces\Commentable;

// initiate the comment repository to use it when fetching comments
$commentRepository = $entityManager->getRepository('Comment');


///////////////////////////////////////////////////////////////////////////
function processSaveCommentRequest($object) {
    global $entityManager;

    try {
        $comment = prepareCommentForPersisting($object);
        $entityManager->persist($comment);
        $entityManager->flush();

        return [
            'status' => true,
            'html'   => renderContentWithNoLayout('elements/single-comment.php', ['comment' => $comment->getCommentDetails()]),
        ];
    }
    catch (ValidationException $e) {
        return [
            'status' => false,
            'errors' => $e->getErrors(),
        ];
    }
    catch (LogicException $e) {
        return [
            'status' => false,
            'errors' => ['global' => 'Няма как да добавите коментар тук.<br /> Моля, докладвайте грешката през страница «Контакт».'],
        ];
    }
}

///////////////////////////////////////////////////////////////////////////
function prepareCommentForPersisting($object): Comment {
    $objectClass = ClassUtils::getClass($object);

    // abort request for entities which don't implement the Commentable interface
    if ( ! $object instanceof Commentable) {
        throw new LogicException("Class '{$objectClass}' is not commentable.");
    }

    $requestVars = getRequestVariables('POST', ['username', 'comment']);
    $ip          = $_SERVER['REMOTE_ADDR'];
    
    $comment = new Comment();
    $comment->setUsername($requestVars['username'])
            ->setBody($requestVars['comment'])
            ->setIp($ip)
            ->setEntityClass($objectClass)
            ->setEntityId($object->getId())
            ->setCreatedAt();

    return $comment;
}