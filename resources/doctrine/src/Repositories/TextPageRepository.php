<?php
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityRepository;

class TextPageRepository extends EntityRepository {

    ///////////////////////////////////////////////////////////////////////////
    public function findBySlug(?string $slug): ?TextPage {
        // even though home page's content is located here,
        // the page should not be accessible by its slug
        return ($slug !== 'home')
               ? $this->findOneBy(['slug' => $slug])
               : NULL;
    }
    
}