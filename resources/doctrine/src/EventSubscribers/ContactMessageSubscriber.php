<?php
use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Util\ClassUtils;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Exceptions\ValidationException;

class ContactMessageSubscriber implements EventSubscriber {

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

        if ( ! $entity instanceof ContactMessage) {
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

        if ( ! $entity instanceof ContactMessage) {
            return;
        }

        // get the email addresses which need to receive a notification
        $emails = $this->getNotificationEmailAddresses('contact_message_notification_emails');

        // if there are no addresses, abort sending of notification emails
        if (count($emails) === 0) {
            return ;
        }

        $emailContent = $this->prepareEmail($emails, $entity);

        $this->sendNotificationEmail($emailContent);
    }

    ///////////////////////////////////////////////////////////////////////////
    private function validate(ContactMessage $entity): bool {
        $username = $entity->getUsername();
        $email    = $entity->getEmail();
        $body     = $entity->getBody();

        $usernameMaxLength = 255;
        $emailMaxLength    = 255;
        $bodyMaxLength     = 65536;

        // don't allow empty username
        if (trim($username) === '') {
            $this->addError('Моля, попълнете полето за име.', 'username');
        }
        elseif ($this->checkStringAgainstMaxLength($username, $usernameMaxLength)) {
            $this->addError('Дължината на поле «Име» не може да надвишава ' . $usernameMaxLength . ' символа.', 'username');
        }

        // check email validity
        if (isset($email) && $email !== '' &&  !isEmailValid($email)) {
            $this->addError('Моля, въведете валиден имейл адрес.', 'email');
        }
        elseif ($this->checkStringAgainstMaxLength($email, $emailMaxLength)) {
            $this->addError('Дължината на поле «E-mail адрес» не може да надвишава ' . $emailMaxLength . ' символа.', 'body');
        }

        // don't allow empty message body either
        if (trim($body) === '') {
            $this->addError('Моля, попълнете полето за съобщение.', 'message');
        }
        elseif ($this->checkStringAgainstMaxLength($body, $bodyMaxLength)) {
            $this->addError('Дължината на поле «Съобщение» не може да надвишава ' . $bodyMaxLength . ' символа.', 'body');
        }

        // check if captcha matches
        if ( ! $this->isCaptchaCorrect()) {
            $this->addError('Моля, попълнете анти-спам защитата.', 'captcha');
        }

        // check whether X minutes have passed between consecutive messages
        $minutes       = $this->timeoutBetweenRequests;
        $messagesCount = $this->repository->messagesCountInLastMinutes($entity->getIp(), $minutes);
        if ($messagesCount > 0) {
            $this->addError('От съображение за сигурност не можете да публикувате 
                             две последователни съобщения в рамките на ' . $minutes . ' минути.');
        }

        // if there have been errors, return false
        return (bool) (count($this->getErrors()) === 0);
    }

    ///////////////////////////////////////////////////////////////////////////
    private function prepareEmail(array $emails, ContactMessage $message): array {
        $subject = 'Ново контактно съобщение от сайта';

        $body    = '<strong>Име:</strong> ' . escape($message->getUsername()) . '<br />';
        $body   .= '<strong>E-mail:</strong> ' . ($message->getEmail() ?? '--') . '<br />';
        $body   .= '<strong>IP:</strong> ' . $message->getActualIp() . '<br />';
        $body   .= '<strong>Съобщение:</strong> ' . escape($message->getBody()) . '<br />';

        // add message date to subject
        $date     = beautifyDate('%A, %d.%m.%Y г., %H:%M ч.', $message->getCreatedAt());
        $subject .= ' (' . $date . ')';

        return [
            'subject'    => $subject,
            'body'       => $body,
            'recipients' => $emails,
        ];
    }
    
}
