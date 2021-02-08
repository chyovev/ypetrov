<?php
class Video {
    private $id;

    private $active;

    private $title;

    private $slug;

    private $summary;

    private $views;

    private $ord;

    private $createdAt;

    private $modifiedAt;

    ///////////////////////////////////////////////////////////////////////////
    public function getVideoDetails(): array {
        $video = [
            'id'      => $this->getId(),
            'active'  => $this->getActive(),
            'title'   => $this->getTitle(),
            'slug'    => $this->getSlug(),
            'summary' => $this->getSummary(),
            'video'   => $this->getVideo(),
        ];

        return $video;
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
    private function getSummary(): ?string {
        return $this->summary;
    }

    ///////////////////////////////////////////////////////////////////////////
    private function getViews(): int {
        return $this->views;
    }

    ///////////////////////////////////////////////////////////////////////////
    private function setViews(int $views): self {
        $this->views = $views;

        return $this;
    }

    ///////////////////////////////////////////////////////////////////////////
    public function incrementViews(): void {
        $currentViewCount = $this->getViews();
        $this->setViews($currentViewCount + 1);
    }

    ///////////////////////////////////////////////////////////////////////////
    // currently videos are stored in the videos/ folder,
    // following the «slug».mp4/webm/jpg name convention
    private function getVideo(): array {
        $video = [
            'mp4'  => VIDEO_PATH . '/' . $this->getSlug() . '.mp4',
            'webm' => VIDEO_PATH . '/' . $this->getSlug() . '.webm',
            'jpg'  => VIDEO_PATH . '/' . $this->getSlug() . '.jpg',
        ];

        return $video;
    }

}