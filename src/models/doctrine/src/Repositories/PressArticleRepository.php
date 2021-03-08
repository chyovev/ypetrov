<?php
use Doctrine\ORM\EntityRepository;

class PressArticleRepository extends EntityRepository {

    ///////////////////////////////////////////////////////////////////////////
    public function findActive() {
        return $this->findBy(['active' => 1], ['ord' => 'ASC']);
    }

    ///////////////////////////////////////////////////////////////////////////
    public function findBySlug(?string $slug): ?PressArticle {
        return $this->findOneBy(['slug' => $slug, 'active' => 1]);
    }
    
}