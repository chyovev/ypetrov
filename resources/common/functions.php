<?php

///////////////////////////////////////////////////////////////////////////////
function renderLayoutWithContentFile($contentFile, $variables = []): void {
    $contentFileFullPath = TEMPLATES_PATH . '/' . $contentFile;
 
    // convert the $variables array into single variables
    extract($variables);
 
 
    if ($contentFile === 'error404.php' ||  ! file_exists($contentFileFullPath)) {
        header('HTTP/1.1 404 Not Found'); 
        require_once(LAYOUTS_PATH . '/header.php');
        require_once(LAYOUTS_PATH . '/error404.php');
    }
    else {
        require_once(LAYOUTS_PATH . '/header.php');
        require_once($contentFileFullPath);
    }
 
    require_once(LAYOUTS_PATH . '/footer.php');
}

///////////////////////////////////////////////////////////////////////////////
function renderContentWithNoLayout($contentFile, $variables = []): ?string {
    $contentFileFullPath = TEMPLATES_PATH . '/' . $contentFile;
 
    // convert the $variables array into single variables
    extract($variables);
 
    if (file_exists($contentFileFullPath)) {
        ob_start();
        require($contentFileFullPath);
        return ob_get_clean();
    }

    return NULL;
}

///////////////////////////////////////////////////////////////////////////////
function rederJSONContent($array): void {
    if (!is_array($array)) {
        $array = [$array];
    }

    header('Content-Type: application/json');
    echo json_encode($array);
}

///////////////////////////////////////////////////////////////////////////////
function isRequestAjax(): bool {
    return (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');
}

///////////////////////////////////////////////////////////////////////////////
function throw404OnEmpty($item): void {
    if ( ! $item) {
        renderLayoutWithContentFile('error404.php');
        die;
    }
}

///////////////////////////////////////////////////////////////////////////////
function escape(string $string): string {
    // replace <br /> tag with a space
    $string = preg_replace('/<br ?\/?>/i', ' ', $string);
    return trim(preg_replace('/\s{2,}/i', ' ', htmlspecialchars(strip_tags($string))));
}

///////////////////////////////////////////////////////////////////////////////
function setGlobalNavigation(array $array): void {
    $GLOBALS['navigation'] = $array;
}

///////////////////////////////////////////////////////////////////////////////
function getGlobalNavigation(): array {
    return $GLOBALS['navigation'] ?? [];
}

///////////////////////////////////////////////////////////////////////////////
function setCurrentNavPage(string $fileName, ?string $slug): void {
    $GLOBALS['currentPage'] = [
        'fileName' => $fileName,
        'slug'     => $slug,
    ];
}

///////////////////////////////////////////////////////////////////////////////
function getCurrentNavPage(): array {
    return $GLOBALS['currentPage'] ?? [];
}
