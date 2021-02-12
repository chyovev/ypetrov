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
function isRequest(string $type): bool {
    return (strtolower($_SERVER['REQUEST_METHOD']) === strtolower($type));
}

///////////////////////////////////////////////////////////////////////////////
function throw404OnEmpty($item): void {
    if ( ! $item) {
        renderLayoutWithContentFile('error404.php');
        die;
    }
}

///////////////////////////////////////////////////////////////////////////////
function escape(?string $string): string {
    // replace <br /> tag with a space
    $string = preg_replace('/<br ?\/?>/i', ' ', $string);
    return trim(preg_replace('/\s{1,}/i', ' ', htmlspecialchars(strip_tags($string))));
}

///////////////////////////////////////////////////////////////////////////////
function wordWrapCut(
        string $string,
        int    $length = 80,
        string $etc = '...',
        bool   $break_words = false,
        bool   $middle = false,
        string $encoding = 'UTF-8'
    ): string {

    $string = strip_tags($string);
    $string = preg_replace('/\s+/i', ' ', $string);

    if (($length === 0) || ($string === '')) {
        return '';
    }

    if (mb_strlen($string, $encoding) > $length) {
        $length -= min($length, mb_strlen($etc, $encoding));
        if (!$break_words && !$middle) {
            $string = preg_replace(
                '/\s+?(\S+)?$/u',
                '',
                mb_substr($string, 0, $length + 1, $encoding)
            );
        }
        if (!$middle) {
            return mb_substr($string, 0, $length, $encoding) . $etc;
        }
        return mb_substr($string, 0, $length / 2, $encoding) . $etc .
               mb_substr($string, -$length / 2, $length, $encoding);
    }

    return $string;
}

///////////////////////////////////////////////////////////////////////////////
function truncate(string $string, int $length): string {
    return wordWrapCut(escape($string), $length);
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
function setCurrentNavPage(string $fileName, ?string $slug = NULL): void {
    $GLOBALS['currentPage'] = [
        'fileName' => $fileName,
        'slug'     => $slug,
    ];
}

///////////////////////////////////////////////////////////////////////////////
function getCurrentNavPage(): array {
    return $GLOBALS['currentPage'] ?? [];
}

///////////////////////////////////////////////////////////////////////////////
function getImageDimensions(string $relativeImage): array {
    $completeImagePath = HOST_URL . $relativeImage;
    $dimensions        = @getimagesize($completeImagePath);

    if ($dimensions) {
        return ['width' => $dimensions[0], 'height' => $dimensions[1]];
    }

    return [];
}

///////////////////////////////////////////////////////////////////////////////
function getGetRequestVar(string $var) {
    $vars = getRequestVariables('GET', [$var], true);
    return $vars[$var];
}

///////////////////////////////////////////////////////////////////////////////
function getRequestVariables(string $type, array $vars, $defaultNull = false) {
    $requestTypes = [
        'POST' => $_POST,
        'GET'  => $_GET,
    ];

    // only predetermined types are allowed 
    if ( ! array_key_exists(strtoupper($type), $requestTypes)) {
        throw new LogicException('Type ' . $type . ' is not supported');
    }

    $request = $requestTypes[strtoupper($type)];

    $result  = [];

    foreach ($vars as $var) {
        $result[$var] = isset($request[$var])
                      ? $request[$var]
                      : ($defaultNull ? NULL : '');
    }

    return $result;
}

///////////////////////////////////////////////////////////////////////////////
function beautifyDate(string $pattern, DateTime $source): string {
    return strftime($pattern, $source->getTimestamp());
}
