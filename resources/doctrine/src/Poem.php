<?php
use Doctrine\Common\Collections\ArrayCollection;

class Poem {
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
    public function getPoemDetails(): array {
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
    private function getId(): int {
        return $this->id;
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
    private function getDedication(): ?string {
        return $this->dedication;
    }

    ///////////////////////////////////////////////////////////////////////////
    private function getBody(): string {
        return $this->body;
    }

    ///////////////////////////////////////////////////////////////////////////
    private function getReadCount(): int {
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

    ///////////////////////////////////////////////////////////////////////////
    private function getUseMonospaceFont(): bool {
        return $this->useMonospaceFont;
    }

    ///////////////////////////////////////////////////////////////////////////
    private function getBooks(): array {
        return $this->books;
    }

}