<?php

use Doctrine\ORM\EntityRepository;

class ContactMessageRepository extends EntityRepository {

    ///////////////////////////////////////////////////////////////////////////
    // check how many contact messages are written in the last $minutes minutes
    // from $ip IP address
    public function messagesCountInLastMinutes(int $ip, int $minutes) {
        $pastDateTime = new DateTime('-' . $minutes . ' min');

        return $this->createQueryBuilder('c')
                    ->select('count(c.id)')
                    ->where('c.ip = :ip AND c.createdAt >= :past_date')
                    ->setParameter('ip', $ip)
                    ->setParameter('past_date', $pastDateTime)
                    ->getQuery()
                    ->getSingleScalarResult();
    }

    ///////////////////////////////////////////////////////////////////////////
    public function prepareMessageFromPostRequest(): ContactMessage {
        $requestVars = Router::getPostParams(['username', 'email', 'message']);
        $ip          = $_SERVER['REMOTE_ADDR'];
        
        $message = new ContactMessage();
        $message->setUsername($requestVars['username'])
                ->setEmail($requestVars['email'])
                ->setBody($requestVars['message'])
                ->setIp($ip)
                ->setCreatedAt();

        return $message;
    }
    
}