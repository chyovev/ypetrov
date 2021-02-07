<?php
use Doctrine\ORM\EntityRepository;

class BookRepository extends EntityRepository {

    ///////////////////////////////////////////////////////////////////////////
    public function getAllActiveBooks() {
        return $this->findBy(['active' => 1], ['ord' => 'ASC']);
    }

    ///////////////////////////////////////////////////////////////////////////
    public function findBySlug($slug) {
        $queryBuilder = $this->createQueryBuilder('book')
                             ->andWhere('book.slug = :slug AND book.active = 1') // fetch only active book with this slug
                             ->setParameter('slug', $slug)
                             ->leftJoin('book.contents', 'contents')             // join the contents table
                             ->andWhere('contents.active = 1')                   // and add condition for active field
                             ->addSelect('contents')                             // fetch ONLY active contents
                             ->getQuery()
                             ->execute();

       return $queryBuilder[0] ?? NULL;
    }
    
}