<?php
use Doctrine\ORM\EntityRepository;

class EssayRepository extends EntityRepository {

    ///////////////////////////////////////////////////////////////////////////
    public function findActive() {
        return $this->findBy(['active' => 1], ['ord' => 'ASC']);
    }

    ///////////////////////////////////////////////////////////////////////////
    public function findBySlug(?string $slug): ?Essay {
        return $this->findOneBy(['slug' => $slug, 'active' => 1]);
    }
    
}