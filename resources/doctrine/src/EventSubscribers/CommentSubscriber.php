<?php
use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Util\ClassUtils;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Exceptions\ValidationException;

class CommentSubscriber implements EventSubscriber {

    use MailerTrait, ValidationTrait;

    private $entityManager;
    private $repository;

    private $timeoutBetweenRequests = 2; // simple timeout spam prevention
    private $errors = [];

    ///////////////////////////////////////////////////////////////////////////
    public function getSubscribedEvents(): array {
        return [Events::prePersist, Events::postPersist];
    }

    ///////////////////////////////////////////////////////////////////////////
    // validate comment before persisting it
    public function prePersist(LifecycleEventArgs $args): void {
        $entity = $args->getObject();

        $this->entityManager = $args->getObjectManager();
        $this->repository    = $this->entityManager->getRepository( ClassUtils::getClass($entity) );

        // the listener gets called for all entities,
        // but is needed only for Comments
        if ( ! $entity instanceof Comment) {
            return;
        }

        // if there are validation errors, throw them as an exception
        if ( ! $this->validate($entity)) {
            $errors = $this->getErrors();
            throw new ValidationException($errors);
        }
    }

    ///////////////////////////////////////////////////////////////////////////
    // send a notification email after persisting a comment
    public function postPersist(LifecycleEventArgs $args): void {
        $entity = $args->getObject();

        $this->entityManager = $args->getObjectManager();

        if ( ! $entity instanceof Comment) {
            return;
        }

        // get the email addresses which need to receive a notification
        $emails = $this->getNotificationEmailAddresses('comment_notification_emails');

        // if there are no addresses, abort sending of notification emails
        if (count($emails) === 0) {
            return ;
        }

        $linkToPage   = $this->getLinkToPage($entity);
        $emailContent = $this->prepareEmail($emails, $entity, $linkToPage);

        $this->sendNotificationEmail($emailContent);
    }

    ///////////////////////////////////////////////////////////////////////////
    private function validate(Comment $entity): bool {
        $username = $entity->getUsername();
        $body     = $entity->getBody();

        $usernameMaxLength = 255;
        $bodyMaxLength     = 65536;

        // don't allow empty username
        if (trim($username) === '') {
            $this->addError('Моля, попълнете полето за име.', 'username');
        }
        elseif ($this->checkStringAgainstMaxLength($username, $usernameMaxLength)) {
            $this->addError('Дължината на поле «Име» не може да надвишава ' . $usernameMaxLength . ' символа.', 'username');
        }

        // don't allow empty comment body either
        if (trim($body) === '') {
            $this->addError('Моля, попълнете полето за коментар.', 'comment');
        }
        elseif ($this->checkStringAgainstMaxLength($body, $bodyMaxLength)) {
            $this->addError('Дължината на поле «Коментар» не може да надвишава ' . $bodyMaxLength . ' символа.', 'body');
        }

        // check whether X minutes have passed between consecutive comments
        $minutes      = $this->timeoutBetweenRequests;
        $commentCount = $this->repository->commentCountInLastMinutes($entity->getIp(), $minutes);
        if ($commentCount > 0) {
            $this->addError('От съображение за сигурност не можете да публикувате 
                             два последователни коментара в рамките на ' . $minutes . ' минути.');
        }

        // if there have been errors, return false
        return (bool) (count($this->getErrors()) === 0);
    }

    ///////////////////////////////////////////////////////////////////////////
    private function getLinkToPage(Comment $comment): ?array {
        $entityClass = $comment->getEntityClass();
        $entityId    = $comment->getEntityId();

        switch ($entityClass) {
            case 'Poem':
                return $this->getPoemLink($entityId);
                break;
            case 'Video':
                return $this->getVideoLink($entityId);
                break;
        }

        return NULL;
    }

    ///////////////////////////////////////////////////////////////////////////
    private function getPoemLink(int $id): array {
        $poemRepository = $this->entityManager->getRepository('Poem');
        $poemObject     = $poemRepository->findOneBy(['id' => $id]);
        $poemDetails    = $poemObject->getPoemDetails();
        $contents       = $poemObject->getContentsAsArray();

        if (isset($contents[0])) {
            $bookObject  = $contents[0]->getBook();
            $bookDetails = $bookObject->getBookDetails();
            $url         = HOST_URL . Url::generatePoemUrl($bookDetails['slug'], $poemDetails['slug']);

            return ['стихотворение', $url, $poemDetails['title']];
        }
    }

    ///////////////////////////////////////////////////////////////////////////
    private function getVideoLink(int $id): array {
        $videoRepository = $this->entityManager->getRepository('Video');
        $videoObject     = $videoRepository->findOneBy(['id' => $id]);
        $videoDetails    = $videoObject->getVideoDetails();
        $url             = HOST_URL . Url::generateVideoUrl($videoDetails['slug']);

        return ['видео', $url, $videoDetails['title']];
    }

    ///////////////////////////////////////////////////////////////////////////
    private function prepareEmail(array $emails, Comment $comment, ?array $linkToPage = NULL): array {
        $subject = 'Нов коментар на сайта';

        $body    = '<strong>Име:</strong> ' . escape($comment->getUsername()) . '<br />';
        $body   .= '<strong>IP:</strong> ' . $comment->getActualIp() . '<br />';
        $body   .= '<strong>Коментар:</strong> ' . escape($comment->getBody()) . '<br />';

        // if there's a link to page, add info about it in subject and body
        if ($linkToPage) {
            list ($type, $url, $title) = $linkToPage;

            $subject .= ' към ' . $type . ' „' . escape($title) . '“';
            $body    .= '<strong>Линк:</strong> <a href="' . $url . '">' . escape($title) . '</a><br />';
        }

        // add comment date to subject
        $date     = beautifyDate('%A, %d.%m.%Y г., %H:%M ч.', $comment->getCreatedAt());
        $subject .= ' (' . $date . ')';

        return [
            'subject'    => $subject,
            'body'       => $body,
            'recipients' => $emails,
        ];
    }
    
}
