<?php
use Interfaces\Commentable;

class TextPage implements Commentable {

    private $id;

    private $title;

    private $shortTitle;

    private $slug;

    private $body;

    private $readCount;

    private $createdAt;

    private $modifiedAt;

    ///////////////////////////////////////////////////////////////////////////
    public function getDetails(): array {
        $essay = [
            'id'          => $this->getId(),
            'title'       => $this->getTitle(),
            'short_title' => $this->getShortTitle(),
            'slug'        => $this->getSlug(),
            'body'        => $this->getBody(),
            'read_count'  => $this->getReadCount(),
        ];

        return $essay;
    }

    ///////////////////////////////////////////////////////////////////////////
    public function getId(): int {
        return $this->id;
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