<?php
use Doctrine\ORM\EntityRepository;

class GalleryRepository extends EntityRepository {

    ///////////////////////////////////////////////////////////////////////////
    public function getAllActiveGalleryImages() {
        return $this->findBy(['active' => 1], ['ord' => 'ASC']);
    }
    
}