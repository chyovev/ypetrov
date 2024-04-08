<?php

/**
 * Check if the current page's route has
 * a name which matches on of the patterns.
 * 
 * @param  mixed  ...$patterns
 * @return bool
 */
function isRoute(...$patterns): bool {
    $route = request()->route();

    // in some cases (like error pages) there simply
    // is no route, so there's nothing to match
    if ( ! $route) {
        return false;
    }

    return $route->named($patterns);
}

/**
 * Split a string into words. Use preg_split instead of a
 * simple white-space splitter, as we need to get rid of
 * any punctuation.
 * 
 * Regex explanation:
 *     ^ within [] = match anything BUT:
 *           \p{L} = any kind of letter from any language
 *           \p{N} = any kind of numeric character in any script
 * 
 * @param  string $string
 * @return array
 */
function splitIntoWords(string $string): array {
    return array_filter(array_unique(preg_split('/[^\p{L}\p{N}]+/u', $string)));
}

/**
 * Highlight all parts of a string inside a text.
 * 
 * @param  string $text
 * @param  string $string
 * @return string
 */
function highlightSubstring(string $text, string $string): string {
    $words = splitIntoWords($string);

    return preg_replace('/(' . implode('|', $words) . ')/ui', '<strong>$1</strong>', $text);
}