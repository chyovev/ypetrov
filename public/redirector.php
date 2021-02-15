<?php
require_once('../resources/autoload.php');

$redirectFile         = 'redirects.txt';
$redirectFolder       = realpath(COMMON_PATH . '/..');
$redirectFileFullPath = $redirectFolder . '/' . $redirectFile;

$redirectContent = @file_get_contents($redirectFileFullPath);

// get current address, strip the root part and decode cyrillic characters
$requestUri      = preg_replace('/^('.preg_quote(WEBROOT, '/').')/i', '', $_SERVER['REQUEST_URI']);
$requestUriClean = rawurldecode($requestUri);

// if the file could not be opened, log the error
if ($redirectContent === false) {
    Logger::logError('301 Redirects file does not exist or is unreadable. (' . $redirectFile . ')');
}

// otherwise proceed with the redirects
else {
    $allRedirects  = [];
    $redirectLines = array_filter(explode("\n", $redirectContent));

    foreach ($redirectLines as $line) {
        list($old, $new)      = preg_split('/\s+/i', $line, 2);
        $allRedirects[ $old ] = $new;
    }

    // try to find new address using raw current URI and then clean current URI
    $newAddress = $allRedirects[$requestUri] ?? $allRedirects[$requestUriClean] ?? NULL;

    // if there was no match whatsoever, redirect
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

// if there was no match, show error 404
renderLayoutWithContentFile('error404.php');