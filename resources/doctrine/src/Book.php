<?php
use Doctrine\Common\Collections\ArrayCollection;
use Interfaces\Commentable;

class Book implements Commentable {
    private $id;

    private $active;

    private $title;

    private $slug;

    private $publisher;

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
    public function getDetails($contents = false): array {
        $book = [
            'id'             => $this->getId(),
            'active'         => $this->getActive(),
            'title'          => $this->getTitle(),
            'slug'           => $this->getSlug(),
            'publisher'      => $this->getPublisher(),
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
    public function getId(): int {
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
    private function getPublisher(): ?string {
        return $this->publisher;
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
            $poem              = $item->getPoem()->getDetails();
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