<?php

///////////////////////////////////////////////////////////////////////////////
function renderLayoutWithContentFile($contentFile, $variables = []): void {
    $contentFileFullPath = TEMPLATES_PATH . '/' . $contentFile;
 
    // convert the $variables array into single variables
    extract($variables);
 
    if ($contentFile === 'error404.php' ||  ! file_exists($contentFileFullPath)) {
        header('HTTP/1.1 404 Not Found'); 
        Logger::logError('Resource not found');
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
        if ( ! IS_DEV) {
            renderLayoutWithContentFile('error404.php');
            die;
        }

        $backtrace = debug_backtrace();
        throw new Exception('Backtrace: calling function "' . $backtrace[0]['function'] . '" in file ' . $backtrace[0]['file']);
    }
}

///////////////////////////////////////////////////////////////////////////////
function escape(?string $string): string {
    // replace <br /> tag with a space
    $string = preg_replace('/<br ?\/?>/i', ' ', $string);
    return trim(preg_replace('/\s{1,}/i', ' ', htmlspecialchars(strip_tags($string))));
}

///////////////////////////////////////////////////////////////////////////////
function truncateString(
        string $string,
        int    $targetLength = 80,
        string $suffix       = '...',
        bool   $breakWords   = false,
        string $encoding     = 'UTF-8'
    ): string {

    // removing tags, unify white spaces and trim input string
    $string = strip_tags($string);
    $string = trim(preg_replace('/\s/', ' ', $string));

    // calculate string length after clean up
    $stringLength = mb_strlen($string, $encoding);

    // if target length is too small or too big, return string
    if (($targetLength <= 0) || $targetLength >= $stringLength) {
        return $string;
    }

    // subtract suffix length from target length
    $targetLength -= min($targetLength, mb_strlen($suffix, $encoding));

    // if words can be broken, simply cut the string and add suffix
    if ($breakWords) {
        return mb_substr($string, 0, $targetLength, $encoding) . $suffix;
    }

    // otherwise cut one character too long and replace last word (if any) with suffix
    $string = mb_substr($string, 0, $targetLength + 1, $encoding);
    
    return preg_replace('/\s+?(\S+)?$/u', $suffix, $string);
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
function setCurrentNavPage(string $fileName, ?string $slug = NULL, bool $noindex = false): void {
    $GLOBALS['currentPage'] = [
        'fileName' => $fileName,
        'slug'     => $slug,
        'noindex'  => $noindex,
    ];
}

///////////////////////////////////////////////////////////////////////////////
function getCurrentNavPage(): array {
    return $GLOBALS['currentPage'] ?? [];
}

///////////////////////////////////////////////////////////////////////////////
function isCurrentPageFile(string $fileName): bool {
    $currentPage = getCurrentNavPage();

    return (isset($currentPage['fileName']) && $currentPage['fileName'] == $fileName);
}

///////////////////////////////////////////////////////////////////////////////
function isCurrentPageSlug(string $slug): bool {
    $currentPage = getCurrentNavPage();

    return (isset($currentPage['slug']) && $currentPage['slug'] == $slug);   
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
function beautifyDate(string $pattern, ?DateTime $source = NULL): ?string {
    if ( ! $source) {
        return NULL;
    }
    
    return strftime($pattern, $source->getTimestamp());
}

///////////////////////////////////////////////////////////////////////////////
function getTextSample(string $haystack, string $needle, int $wordsAround) {
    // separate all words of the needle, sort them by length,
    // add the needle as a whole to the beginning
    // and filter out duplicates
    $needleWords = explodeWords($needle);
    usort($needleWords, 'sortArrayByLength');
    array_unshift($needleWords , $needle);
    $needleWords = array_filter($needleWords);

    // then try to find context for each element
    foreach ($needleWords as $word) {
        $context = showStringInContext($haystack, $word, $wordsAround);
        if ($context !== false) {
            break;
        }
    }

    // if there was no match,
    // fallback to truncating haystack from beginning
    if ( ! $context) {
        $context = cutFirstNWords($haystack, $wordsAround);
    }

    // bolden needle in haystack while keeping original case
    $context = outlineElementsInText($needleWords, $context);

    return $context;
}

///////////////////////////////////////////////////////////////////////////////
function showStringInContext(string $haystack, string $needle, int $wordsAround, string $encoding = 'utf-8') {
    $haystack       = ' ' . escape($haystack) . ' ' ;
    $haystackLength = mb_strlen($haystack, $encoding);

    // if the needle is not present in the haystack at all, return false
    if (($needlePos = mb_stripos($haystack, $needle, 0, $encoding)) === false) {
        return false;
    }

    // add context marker at beginning and end (if text is in the middle)
    $contextMarker = '...';

    // how many words to get on the left and right
    $wordsLeft    = (int) ($wordsAround / 2);
    $wordsRight   = $wordsAround + 1;
    $stashedWords = 0;

    // start searching for words both directions where the needle was found
    $startPos = $needlePos;
    $endPos   = $needlePos;

    // start going left character by character
    // and break only after $wordsLeft matches $stashedWords
    // or the beginning of the string has been reached
    while ($stashedWords < $wordsLeft && $startPos > 0) {
        $startPos--;
        if (mb_substr($haystack, $startPos, 1, $encoding) === ' ') {
            $stashedWords++;
        }
    }

    // do the same when going right and stop if end of haystack is reached
    // or $stashedWords matches $wordsRight
    while ($stashedWords < $wordsRight && $endPos < $haystackLength) {
        $endPos++;
        if (mb_substr($haystack, $endPos, 1, $encoding) === ' ') {
            $stashedWords++;
        }
    }

    // finally if not enough words were stashed and the $startPos is still not
    // at the beginning of the haystack, stash some more
    while ($stashedWords < $wordsRight && $startPos > 0) {
        $startPos--;
        if (mb_substr($haystack, $startPos, 1, $encoding) === ' ') {
            $stashedWords++;
        }
    }

    $trimmed = trim(mb_substr($haystack, $startPos, ($endPos - $startPos), $encoding));
    $context = $trimmed;

    // add prefix marker if beginning of haystack does not match beginning of context
    // (1 because spaces were added to surround the haystack)
    if (mb_strpos($haystack, $trimmed, 0, $encoding) !== 1) {
        $context = $contextMarker . $trimmed;
    }

    // add suffix marker if haystack ends on context + white space
    if (preg_match('/' . preg_quote($trimmed) . '\s$/ui', $haystack) == 0) {
        $context .= $contextMarker;
    }

    return $context;
}

///////////////////////////////////////////////////////////////////////////////
function cutFirstNWords(string $string, int $wordsCount, string $encoding = 'utf-8'): string {
    if ($wordsCount <= 1) {
        return $string;
    }

    // remove html tags and double spaces
    $string       = escape($string);
    $stringLength = mb_strlen($string, $encoding);
    $stashedWords = 0;
    $endPos       = 0;

    // until the needed words are collected,
    // get the position of space with offset = last space pos + 1
    while ($stashedWords < $wordsCount) {
        // when there is no next space, break the cycle
        if (($spacePos = mb_strpos($string, ' ', $endPos, $encoding)) === false) {
            break;
        }
        $endPos = $spacePos + 1;
        $stashedWords++;
    }

    // if there were no spaces to begin with, return the original string
    if ( ! $stashedWords) {
        return $string;
    }

    $cut = mb_substr($string, 0, $endPos - 1, 'utf-8');

    // if the length of the cut version is shorter than source,
    // add suffix
    if (mb_strlen($cut, 'utf-8') < $stringLength) {
        $cut .= '...';
    }

    return $cut;
}

///////////////////////////////////////////////////////////////////////////////
// split words into array
function explodeWords(?string $string = NULL): array {
    return preg_split('~[^\p{L}\p{N}\']+~u', $string);
}

///////////////////////////////////////////////////////////////////////////////
function sortArrayByLength($a, $b){
    return mb_strlen($b, 'utf-8') - mb_strlen($a, 'utf-8');
}

///////////////////////////////////////////////////////////////////////////////
function outlineElementsInText(array $words, string $haystack): string {
    return preg_replace('/(' . implode('|', $words) . ')/ui', '<strong>$1</strong>', $haystack);
}

///////////////////////////////////////////////////////////////////////////////
function isEmailValid(string $email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}
