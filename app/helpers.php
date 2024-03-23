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
