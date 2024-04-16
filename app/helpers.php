<?php

use App\Helpers\SEO;

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

/**
 * Clean-up a string by removing all tags
 * and extra white spaces.
 * 
 * @param  string $string
 * @return string
 */
function cleanString(string $string = null) {
    return is_null($string)
        ? $string
        : trim(preg_replace('/\s+/', ' ', strip_tags($string)));
}

/**
 * Initialize SEO object for non-dynamic pages.
 * 
 * @param  string $title
 * @param  string|null $description
 * @return SEO
 */
function seo(string $title, string $description = null): SEO {
    return new SEO($title, $description);
}