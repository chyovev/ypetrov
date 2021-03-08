<?php
use Doctrine\Common\Collections\ArrayCollection;

class BookContent {
    private $id;

    private $active;

    private $ord;

    private $createdAt;

    private $modifiedAt;

    private $book;

    private $poem;

    ///////////////////////////////////////////////////////////////////////////
    public function getId(): int {
        return $this->id;
    }

    ///////////////////////////////////////////////////////////////////////////
    public function getActive(): bool {
        return $this->active;
    }

    ///////////////////////////////////////////////////////////////////////////
    public function getBook(): Book {
        return $this->book;
    }

    ///////////////////////////////////////////////////////////////////////////
    public function getPoem(): Poem {
        return $this->poem;
    }

}