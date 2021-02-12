<?php
use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Util\ClassUtils;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Exceptions\ValidationException;

class CommentValidatorSubscriber implements EventSubscriber {

    private $entityManager;
    private $repository;

    private $consecutiveCommentsTimeoutMinutes = 2; // simple timeout spam prevention
    private $errors = [];

    ///////////////////////////////////////////////////////////////////////////
    public function getSubscribedEvents(): array {
        return [Events::prePersist];
    }

    ///////////////////////////////////////////////////////////////////////////
    public function prePersist(LifecycleEventArgs $args): void {
        $entity = $args->getObject();

        $this->entityManager = $args->getObjectManager();
        $this->repository    = $this->entityManager->getRepository( ClassUtils::getClass($entity) );

        // the listener gets called for all entities,
        // but is needed only for Comments
        if ($entity instanceof Comment) {

            // if there are validation errors, throw them as an exception
            if ( ! $this->validate($entity)) {
                $errors = $this->getErrors();
                throw new ValidationException($errors);
            }
        }
    }

    ///////////////////////////////////////////////////////////////////////////
    private function validate(Comment $entity): bool {
        $username = $entity->getUsername();
        $body     = $entity->getBody();

        // don't allow empty username
        if (trim($username) === '') {
            $this->addError('Моля, попълнете полето за подател.', 'username');
        }

        // don't allow empty comment body either
        if (trim($body) === '') {
            $this->addError('Моля, попълнете полето за коментар.', 'comment');
        }

        // check whether X minutes have passed between consecutive comments
        $minutes      = $this->consecutiveCommentsTimeoutMinutes;
        $commentCount = $this->repository->commentCountInLastMinutes($entity->getIp(), $minutes);
        if ($commentCount > 0) {
            $this->addError('От съображения за сигурност не можете да публикувате 
                             два последователни коментара в рамките на ' . $minutes . ' минути.');
        }

        // if there have been errors, return false
        return (bool) (count($this->getErrors()) === 0);
    }

    ///////////////////////////////////////////////////////////////////////////
    private function addError(string $message, string $field = 'general'): void {
        $this->errors[$field][] = $message;
    }

    ///////////////////////////////////////////////////////////////////////////
    private function getErrors(): array {
        return $this->errors;
    }
}