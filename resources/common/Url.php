<?php
// use the static methods of this class to generate URLs
// for all dynamic sections of the project

class Url {
    
    ///////////////////////////////////////////////////////////////////////////////
    public static function generateBookUrl($bookSlug) {
        return WEBROOT . 'tvorchestvo/' . $bookSlug;
    }

    ///////////////////////////////////////////////////////////////////////////////
    public static function generatePoemUrl($bookSlug, $poemSlug) {
        return WEBROOT . 'tvorchestvo/' . $bookSlug . '/' . $poemSlug;
    }

}