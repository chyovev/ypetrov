<?php
use Doctrine\ORM\EntityRepository;

class BookContentRepository extends EntityRepository {

    private $resultsCount = 0;

    ///////////////////////////////////////////////////////////////////////////
    public function searchPoemsAndBooksByString(string $string = NULL): array {
        if ($string === NULL) {
            return [];
        }

        // escape some special characters to avoid the query from getting broken
        $string = preg_replace('/[+\-><\(\)~"@]+/', ' ', $string);

        // Doctrine is not really fond of MATCH AGAINST,
        // so a raw SQL is needed which joins both poems and books tables
        try {
            $sql = "
                SELECT
                P.*,
                MIN(B.`id`)   AS `book_id`,   /* MIN favors older books */
                MIN(B.`slug`) AS `book_slug`,

                /* select match scores to sort by them */
                MAX(MATCH(P.`title`, P.`dedication`, P.`body`) AGAINST (:search IN BOOLEAN MODE)) AS `poem_score`,
                MAX(MATCH(B.`title`)                           AGAINST (:search IN BOOLEAN MODE)) AS `book_score`

                FROM `books_contents` AS BC
                    INNER JOIN `poems` AS P ON BC.`poem_id` = P.`id`
                    INNER JOIN `books` AS B ON BC.`book_id` = B.`id`

                /* match book OR poem, but always active book *and* active book content */
                WHERE      ( MATCH(P.`title`, P.`dedication`, P.`body`) AGAINST (:search IN BOOLEAN MODE)
                        OR   MATCH(B.`title`)                           AGAINST (:search IN BOOLEAN MODE) )
                    AND BC.`active` = 1
                    AND  B.`active` = 1

                /* group by poem to filter out duplicating poems from different books */
                GROUP BY P.`id`

                /* after match score sort by popularity and then by title */
                ORDER BY `poem_score` DESC, `book_score` DESC, P.`read_count` DESC, P.`title` ASC";

            $stmt = $this->getEntityManager()->getConnection()->prepare($sql);

            // pass parameter to query
            $stmt->execute(['search' => $string]);

            $results = $stmt->fetchAll();

            // store total results count
            $this->resultsCount = count($results);

            return $this->groupResultsByBookId($results);
        }

        // if some internal error occurred, log it and show empty results
        catch (Exception $e) {
            Logger::logError($e);
            return [];
        }
    }

    ///////////////////////////////////////////////////////////////////////////
    private function groupResultsByBookId(array $results) {
        $return = [];

        foreach ($results as $item) {
            $return[ $item['book_id'] ][] = $item;
        }

        return $return;
    }

    ///////////////////////////////////////////////////////////////////////////
    public function getResultsCount() {
        return $this->resultsCount;
    }

    
}