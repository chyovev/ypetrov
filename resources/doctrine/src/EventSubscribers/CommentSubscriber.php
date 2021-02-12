<?php
use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Util\ClassUtils;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Exceptions\ValidationException;
use PHPMailer\PHPMailer\PHPMailer;

class CommentSubscriber implements EventSubscriber {

    private $entityManager;
    private $repository;

    private $consecutiveCommentsTimeoutMinutes = 2; // simple timeout spam prevention
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
        $emails = $this->getNotificationEmailAddresses();

        // if there are no addresses, abort sending of notification emails
        if (count($emails) === 0) {
            return ;
        }

        $linkToPage = $this->getLinkToPage($entity);
        $this->sendNotificationEmail($emails, $entity, $linkToPage);
    }

    ///////////////////////////////////////////////////////////////////////////
    private function validate(Comment $entity): bool {
        $username = $entity->getUsername();
        $body     = $entity->getBody();

        $usernameMaxLength = 255;
        $bodyMaxLength     = 65536;

        // don't allow empty username
        if (trim($username) === '') {
            $this->addError('Моля, попълнете полето за подател.', 'username');
        }
        elseif ($this->checkStringAgainstMaxLength($username, $usernameMaxLength)) {
            $this->addError('Дължината на поле «Подател» не може да надвишава ' . $usernameMaxLength . ' символа.', 'username');
        }

        // don't allow empty comment body either
        if (trim($body) === '') {
            $this->addError('Моля, попълнете полето за коментар.', 'comment');
        }
        elseif ($this->checkStringAgainstMaxLength($body, $bodyMaxLength)) {
            $this->addError('Дължината на поле «Коментар» не може да надвишава ' . $bodyMaxLength . ' символа.', 'body');
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
    private function checkStringAgainstMaxLength(string $string, int $length) {
        return (bool) (mb_strlen($string, 'utf-8') > $length);
    }

    ///////////////////////////////////////////////////////////////////////////
    private function addError(string $message, string $field = 'general'): void {
        $this->errors[$field][] = $message;
    }

    ///////////////////////////////////////////////////////////////////////////
    private function getErrors(): array {
        return $this->errors;
    }

    ///////////////////////////////////////////////////////////////////////////
    // email addresses to which to send notifications on new comments
    // are stored in the `config` table following the structure
    // address1,address2,address3
    private function getNotificationEmailAddresses(): array {
        $configRepository = $this->entityManager->getRepository('Config');
        
        $configObject     = $configRepository->findOneBy(['setting' => 'comment_notification_emails']);
        
        // emails stored in the database are assumed to be correct
        // duplicates and empty email addresses are filtered out
        return $configObject ? array_filter(explode(',', $configObject->getValue())) : [];
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
    private function sendNotificationEmail(array $emails, Comment $comment, ?array $linkToPage = NULL): void {
        // load email settings for current environment (set up in config.php)
        global $emailSettings;
        $settings = $emailSettings[!IS_DEV];

        $date    = beautifyDate('%A, %d.%m.%Y г., %H:%M ч.', $comment->getCreatedAt());
        $subject = 'Нов коментар на сайта';
        $body    = '<strong>Име:</strong> ' . escape($comment->getUsername()) . '<br />';
        $body   .= '<strong>IP:</strong> ' . $comment->getActualIp() . '<br />';
        $body   .= '<strong>Коментар:</strong>' . escape($comment->getBody()) . '<br />';

        // if there's a link to page, add info about it in subject and body
        if ($linkToPage) {
            list ($type, $url, $title) = $linkToPage;

            $subject .= ' към ' . $type . ' „' . escape($title) . '“';
            $body    .= '<strong>Линк:</strong> <a href="' . $url . '">' . escape($title) . '</a><br />';
        }

        // add current date to subject
        $subject .= ' (' . $date . ')';

        // enable exceptions so that potential errors can be logged
        $mail = new PHPMailer(true);

        try {
            // server settings
            if ($settings['is_smtp']) {
                $mail->isSMTP();
                $mail->SMTPAuth = $settings['has_smtp_auth'];
            }
            $mail->SMTPDebug  = false;
            $mail->Host       = $settings['host'];
            $mail->Username   = $settings['username'];
            $mail->Password   = $settings['password'];
            $mail->SMTPSecure = $settings['smtp_secure'];
            $mail->Port       = $settings['port'];
            $mail->CharSet    = $settings['charset'];
            $mail->From       = $settings['from_address'];
            $mail->FromName   = $settings['from_name'];

            foreach ($emails as $item) {
                $mail->addAddress($item);
            }

            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $body;
            $mail->AltBody = strip_tags($body);

            $mail->send();
        }
        catch (Exception $e) {
            Logger::logError($mail->ErrorInfo);
        }
    }
}
