<?php
use Doctrine\Common\Collections\ArrayCollection;

class Book {
    private $id;

    private $active;

    private $title;

    private $slug;

    private $publishedYear;

    private $ord;

    private $createdAt;

    private $modifiedAt;

    private $contents;

    ///////////////////////////////////////////////////////////////////////////
    public function __construct() {
        $this->contents = new ArrayCollection();
    }

    /* no need for setters yet, data is only read from the DB */

    ///////////////////////////////////////////////////////////////////////////
    public function getBookDetails($contents = false): array {
        $book = [
            'id'             => $this->getId(),
            'active'         => $this->getActive(),
            'title'          => $this->getTitle(),
            'slug'           => $this->getSlug(),
            'published_year' => $this->getPublishedYear(),
            'image'          => $this->getImage(),
        ];

        // fetch book contents only if needed
        if ($contents) {
            $book['contents'] = $this->getContentsWithSlugAsKey();
        }

        return $book;
    }

    ///////////////////////////////////////////////////////////////////////////
    private function getId(): int {
        return $this->id;
    }

    ///////////////////////////////////////////////////////////////////////////
    private function getActive(): bool {
        return $this->active;
    }

    ///////////////////////////////////////////////////////////////////////////
    private function getTitle(): string {
        return $this->title;
    }

    ///////////////////////////////////////////////////////////////////////////
    private function getSlug(): string {
        return $this->slug;
    }

    ///////////////////////////////////////////////////////////////////////////
    private function getPublishedYear(): int {
        return $this->publishedYear;
    }

    ///////////////////////////////////////////////////////////////////////////
    private function getContentsAsArray(): array {
        return $this->contents->toArray();
    }

    ///////////////////////////////////////////////////////////////////////////
    private function getContentsWithSlugAsKey(): array {
        $result   = [];
        $contents = $this->getContentsAsArray();

        /** @var BookContent $item */
        foreach ($contents as $item) {
            $poem              = $item->getPoem()->getPoemDetails();
            $poemSlug          = $poem['slug'];
            $result[$poemSlug] = $item;
        }

        return $result;
    }

    ///////////////////////////////////////////////////////////////////////////
    // currently images are stored in the covers folder,
    // following the «slug»-«year».jpg name convention
    private function getImage(): string {
        return IMG_CONTENT . '/covers/' . $this->getSlug() . '-' . $this->getPublishedYear() .'.jpg';
    }

}