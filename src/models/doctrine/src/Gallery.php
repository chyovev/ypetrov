<?php
class Gallery {
    private $id;

    private $active;

    private $fileName;

    private $caption;

    private $ord;

    private $createdAt;

    private $modifiedAt;

    ///////////////////////////////////////////////////////////////////////////
    public function getImageDetails(): array {
        $image = [
            'id'        => $this->getId(),
            'active'    => $this->getActive(),
            'file_name' => $this->getFileName(),
            'caption'   => $this->getCaption(),
            'image'     => $this->getImage(),
        ];

        return $image;
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
    private function getFileName(): string {
        return $this->fileName;
    }

    ///////////////////////////////////////////////////////////////////////////
    private function getCaption(): ?string {
        return $this->caption;
    }

    ///////////////////////////////////////////////////////////////////////////
    // currently gallery images are stored in the gallery/ folder
    private function getImage(): array {
        $image = [
            'image' => WEBROOT . 'resources/img/content/gallery/' . $this->getFileName(),
            'thumb' => WEBROOT . 'resources/img/content/gallery/thumbnails/' . $this->getFileName(),
        ];

        return $image;
    }

}