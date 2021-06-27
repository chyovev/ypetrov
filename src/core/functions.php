<?php
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
    // and filter out duplicates and empty elements
    $needleWords = explodeWords($needle);
    usort($needleWords, 'sortArrayByLength');
    array_unshift($needleWords , preg_quote($needle, '/'));
    $needleWords = array_filter(array_unique($needleWords));

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
    if (preg_match('/' . preg_quote($trimmed, '/') . '\s$/ui', $haystack) == 0) {
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
    return array_unique(preg_split('~[^\p{L}\p{N}\']+~u', $string));
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

///////////////////////////////////////////////////////////////////////////////
function setCaptchaSession(string $code): void {
    $_SESSION['code'] = $code;
}

///////////////////////////////////////////////////////////////////////////////
function getCaptchaSession(): ?string {
    return $_SESSION['code'] ?? NULL;
}