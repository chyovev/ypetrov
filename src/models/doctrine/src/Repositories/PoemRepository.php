<?php
use Doctrine\ORM\EntityRepository;

class PoemRepository extends EntityRepository {

    ///////////////////////////////////////////////////////////////////////////
    public function findBySlug($slug) {
        return $this->findOneBy(['slug' => $slug]);
    }

}