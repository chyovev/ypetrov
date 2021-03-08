<?php
use Interfaces\Commentable;

class PressArticle implements Commentable {

    private $id;

    private $active;

    private $press;

    private $publishedDate;

    private $title;

    private $shortTitle;

    private $slug;

    private $body;

    private $readCount;

    private $ord;

    private $createdAt;

    private $modifiedAt;

    ///////////////////////////////////////////////////////////////////////////
    public function getDetails(): array {
        $article = [
            'id'             => $this->getId(),
            'active'         => $this->getActive(),
            'press'          => $this->getPress(),
            'published_date' => $this->getPublishedDate(),
            'title'          => $this->getTitle(),
            'short_title'    => $this->getShortTitle(),
            'slug'           => $this->getSlug(),
            'body'           => $this->getBody(),
            'read_count'     => $this->getReadCount(),
        ];

        return $article;
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
    public function getPress(): ?string {
        return $this->press;
    }

    ///////////////////////////////////////////////////////////////////////////
    public function getPublishedDate(): ?DateTime {
        return $this->publishedDate;
    }

    ///////////////////////////////////////////////////////////////////////////
    public function getTitle(): string {
        return $this->title;
    }

    ///////////////////////////////////////////////////////////////////////////
    public function getShortTitle(): ?string {
        return $this->shortTitle;
    }

    ///////////////////////////////////////////////////////////////////////////
    public function getSlug(): string {
        return $this->slug;
    }

    ///////////////////////////////////////////////////////////////////////////
    public function getBody(): ?string {
        return $this->body;
    }

    ///////////////////////////////////////////////////////////////////////////
    public function getReadCount(): int {
        return $this->readCount;
    }

    ///////////////////////////////////////////////////////////////////////////
    private function setReadCount(int $readCount): self {
        $this->readCount = $readCount;

        return $this;
    }
    
    ///////////////////////////////////////////////////////////////////////////
    public function incrementReadCount(): void {
        $currentReadCount = $this->getReadCount();
        $this->setReadCount($currentReadCount + 1);
    }

}