<?php
// use the static methods of this class to generate URLs
// for all dynamic sections of the project

class Url {
    
    ///////////////////////////////////////////////////////////////////////////////
    public static function generateBookUrl(string $bookSlug): string {
        return WEBROOT . 'tvorchestvo/' . $bookSlug;
    }

    ///////////////////////////////////////////////////////////////////////////////
    public static function generatePoemUrl(string $bookSlug, string $poemSlug): string {
        return WEBROOT . 'tvorchestvo/' . $bookSlug . '/' . $poemSlug;
    }

    ///////////////////////////////////////////////////////////////////////////////
    public static function generateVideoUrl(string $videoSlug): string {
        return WEBROOT . 'video/' . $videoSlug;
    }

    ///////////////////////////////////////////////////////////////////////////////
    public static function generateGalleryUrl(): string {
        return WEBROOT . 'galeriya';
    }

    ///////////////////////////////////////////////////////////////////////////////
    public static function generateSearchUrl(): string {
        return WEBROOT . 'tarsene';
    }

    ///////////////////////////////////////////////////////////////////////////////
    public static function generateContactUrl(): string {
        return WEBROOT . 'kontakt';
    }

    ///////////////////////////////////////////////////////////////////////////////
    public static function generatePressUrl(string $articleSlug): string {
        return WEBROOT . 'presa/' . $articleSlug;
    }

}