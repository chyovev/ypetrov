<?php

abstract class Redirector {

    const FILE = 'redirects.txt';
    private static $urlPairs = []; // array containing old_url as key and new_url as value

    ///////////////////////////////////////////////////////////////////////////////
    public static function checkCurrentPage(): void {
        // if the redirects file could not be loaded for some reason,
        // don't do anything (the error has been logged)
        if ( ! self::populatePairs()) {
            return;
        }

        $url = self::getRequestUrl();

        self::redirectOnMatch($url);
    }

    ///////////////////////////////////////////////////////////////////////////////
    private static function populatePairs(): bool {
        $fileFullPath = realpath(CORE_PATH . '/..') . '/' . self::FILE;
        $fileContent  = @file_get_contents($fileFullPath);

        // if the file could not be opened, log the error and abort
        if ($fileContent === false) {
            Logger::logError('301 Redirects file does not exist or is unreadable. (' . self::FILE . ')');
            return false;
        }
        
        // remove empty lines
        $fileLines   = array_filter(explode("\n", $fileContent));

        // cycle through the file lines, break the URLs into pairs
        // and store them in an array
        foreach ($fileLines as $line) {
            list($old, $new) = preg_split('/\s+/i', $line, 2);

            self::$urlPairs[ $old ] = $new;
        }

        return true;
    }

    ///////////////////////////////////////////////////////////////////////////////
    private static function getRequestUrl(): string {
        $currentUrl = ltrim(Router::getCurrentRequestUrl(), '/'); // remove lading forward slash

        // some search engines have indexed some poems not respecting the .htaccess rules,
        // but directly calling the poems.php file with parameters which bypasses this redirector;
        // in such cases the $currentUrl variable needs to be overridden
        if (preg_match('/^poems.php/ui', $currentUrl)) {
            $bookSlug   = Router::getQueryParam('book');
            $poemSlug   = Router::getQueryParam('poem');
            $currentUrl = 'Творчество/' . $bookSlug;

            // if a poem was specified, add it to the url
            if ($poemSlug) {
                $currentUrl .= '/' . $poemSlug;
            }
            
            return $currentUrl;
        }

        // otherwise strip the root part
        else {
            return preg_replace('/^(' . preg_quote(WEBROOT, '/') . ')/i', '', $currentUrl);
        }
    }

    ///////////////////////////////////////////////////////////////////////////////
    private static function redirectOnMatch(string $url): void {
        // decode non-ascii characters and check url pairs against this, too
        $urlClean  = rawurldecode($url);

        // try to find new address using raw current URI and then clean current URI
        $newAddress = self::$urlPairs[$url] ?? self::$urlPairs[$urlClean] ?? NULL;

        // if there was a match, redirect
        if ($newAddress !== NULL) {
            
            // if the new address does NOT begin with http or https,
            // prepend current host name to it
            if ( ! preg_match('/^https?:\/\//i', $newAddress)) {
                $newAddress = rtrim(WEBROOT_FULL, '/') . $newAddress;
            }

            header('HTTP/1.1 301 Moved Permanently'); 
            header("Location: " . $newAddress);
            die;
        }
    }

}