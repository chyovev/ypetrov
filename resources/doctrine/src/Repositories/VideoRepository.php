<?php
use Doctrine\ORM\EntityRepository;

class VideoRepository extends EntityRepository {

    ///////////////////////////////////////////////////////////////////////////
    public function getAllActiveVideos() {
        return $this->findBy(['active' => 1], ['ord' => 'ASC']);
    }

    ///////////////////////////////////////////////////////////////////////////
    public function findBySlug(?string $slug): ?Video {
        return $this->findOneBy(['slug' => $slug, 'active' => 1]);
    }
    
}