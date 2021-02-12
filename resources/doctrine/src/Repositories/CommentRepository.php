<?php

use Doctrine\Common\Util\ClassUtils;
use Doctrine\ORM\EntityRepository;
use Interfaces\Commentable;

class CommentRepository extends EntityRepository {

    ///////////////////////////////////////////////////////////////////////////
    public function isEntityCommentable($object): bool {
        return ($object instanceof Commentable);
    }
    
    ///////////////////////////////////////////////////////////////////////////
    public function getAllCommentsForEntity($object) {
        $objectClass = ClassUtils::getClass($object);

        if ( ! $this->isEntityCommentable($object)) {
            throw new LogicException("Class '{$objectClass}' is not commentable.");
        }

        $condition = [
            'entityClass' => $objectClass,
            'entityId'    => $object->getId()
        ];

        return $this->findBy($condition);
    }

    ///////////////////////////////////////////////////////////////////////////
    // check how many comments are written in the last $minutes minutes
    // from $ip IP address
    public function commentCountInLastMinutes(int $ip, int $minutes) {
        $pastDateTime = new DateTime('-' . $minutes . ' min');

        return $this->createQueryBuilder('c')
                    ->select('count(c.id)')
                    ->where('c.ip = :ip AND c.createdAt >= :past_date')
                    ->setParameter('ip', $ip)
                    ->setParameter('past_date', $pastDateTime)
                    ->getQuery()
                    ->getSingleScalarResult();
    }
    
}