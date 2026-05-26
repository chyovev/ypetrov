<?php

namespace App\Utils;

class Seo
{

    // default values
    private string $metaTitle       = 'Официален сайт в памет на поета Йосиф Петров (1909 – 2004)';
    private string $metaDescription = 'Йосиф Петров е български поет, общественик и политик';

    ///////////////////////////////////////////////////////////////////////////
    public function __construct(?string $metaTitle = null, ?string $metaDescription = null) {
        if ( ! empty($metaTitle)) {
            $this->setMetaTitle($metaTitle);
        }

        if ( ! empty($metaDescription)) {
            $this->setMetaDescription($metaDescription);
        }
    }

    ///////////////////////////////////////////////////////////////////////////
    private function setMetaTitle(string $metaTitle): void {
        $this->metaTitle = $this->sanitize("{$metaTitle} | Йосиф Петров (1909 – 2004)");
    }

    ///////////////////////////////////////////////////////////////////////////
    public function getMetaTitle(): ?string {
        return $this->metaTitle;
    }

    ///////////////////////////////////////////////////////////////////////////
    private function setMetaDescription(string $metaDescription): void {
        $this->metaDescription = str($this->sanitize($metaDescription))->words(50);
    }

    ///////////////////////////////////////////////////////////////////////////
    public function getMetaDescription(): ?string {
        return $this->metaDescription;
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Remove all HTML tags, new lines and extra white spaces.
     */
    private function sanitize(string $input): string {
        return trim(preg_replace('/\s+/', ' ', strip_tags($input)));
    }

}