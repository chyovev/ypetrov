<?php
use Doctrine\Common\Collections\ArrayCollection;
use Interfaces\Commentable;

class Poem implements Commentable {
    private $id;

    private $title;

    private $slug;

    private $dedication;

    private $body;

    private $readCount;

    private $useMonospaceFont;

    private $createdAt;

    private $modifiedAt;

    private $contents;

    ///////////////////////////////////////////////////////////////////////////
    public function __construct() {
        $this->contents = new ArrayCollection();
    }

    ///////////////////////////////////////////////////////////////////////////
    public function getDetails(): array {
        $poem = [
            'id'                 => $this->getId(),
            'title'              => $this->getTitle(),
            'slug'               => $this->getSlug(),
            'dedication'         => $this->getDedication(),
            'body'               => $this->getBody(),
            'read_count'         => $this->getReadCount(),
            'use_monospace_font' => $this->getUseMonospaceFont(),
        ];

        return $poem;
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
    public function getSlug(): string {
        return $this->slug;
    }

    ///////////////////////////////////////////////////////////////////////////
    public function getDedication(): ?string {
        return $this->dedication;
    }

    ///////////////////////////////////////////////////////////////////////////
    public function getBody(): string {
        return $this->body;
    }

    ///////////////////////////////////////////////////////////////////////////
    public function getReadCount(): int {
        return $this->readCount;
    }

    ///////////////////////////////////////////////////////////////////////////
    public function setReadCount(int $readCount): self {
        $this->readCount = $readCount;

        return $this;
    }

    ///////////////////////////////////////////////////////////////////////////
    public function incrementReadCount(): void {
        $currentReadCount = $this->getReadCount();
        $this->setReadCount($currentReadCount + 1);
    }

    ///////////////////////////////////////////////////////////////////////////
    public function getUseMonospaceFont(): bool {
        return $this->useMonospaceFont;
    }

    ///////////////////////////////////////////////////////////////////////////
    public function getContentsAsArray(): array {
        return $this->contents->toArray();
    }

}