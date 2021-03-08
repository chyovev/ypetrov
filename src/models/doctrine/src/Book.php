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
    public function getActive(): bool {
        return $this->active;
    }

    ///////////////////////////////////////////////////////////////////////////
    public function getTitle(): string {
        return $this->title;
    }

    ///////////////////////////////////////////////////////////////////////////
    public function getSlug(): string {
        return $this->slug;
    }

    ///////////////////////////////////////////////////////////////////////////
    public function getPublisher(): ?string {
        return $this->publisher;
    }

    ///////////////////////////////////////////////////////////////////////////
    public function getPublishedYear(): int {
        return $this->publishedYear;
    }

    ///////////////////////////////////////////////////////////////////////////
    public function getContentsAsArray(): array {
        return $this->contents->toArray();
    }

    ///////////////////////////////////////////////////////////////////////////
    public function getContentsWithSlugAsKey(): array {
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
    public function getImage(): string {
        return WEBROOT . 'resources/img/content/covers/' . $this->getSlug() . '-' . $this->getPublishedYear() .'.jpg';
    }

}