<?php
use Doctrine\ORM\EntityRepository;

class BookRepository extends EntityRepository {

    ///////////////////////////////////////////////////////////////////////////
    public function findActive() {
        $books = $this->findBy(['active' => 1], ['ord' => 'ASC']);

        return $this->useBookIdAsKey($books);
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

    ///////////////////////////////////////////////////////////////////////////
    private function useBookIdAsKey(array $books): array {
        $result = [];

        foreach ($books as $bookObject) {
            $id          = $bookObject->getId();
            $result[$id] = $bookObject;
        }

        return $result;
    }
    
}