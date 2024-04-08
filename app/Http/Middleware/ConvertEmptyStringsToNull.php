<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull as Middleware;

class ConvertEmptyStringsToNull extends Middleware
{

    ///////////////////////////////////////////////////////////////////////////
    /**
     * The middleware's parent transform() method takes care of converting
     * a request's empty string parameters to null values which acts in an
     * unpredicted manner on the search page: passing an empty search string
     * causes a 302 redirect to the previous 'successful'.
     * Since the middleware is global, calling withoutMiddleware() on the
     * search route has no effect. To work around this, the method is
     * extended in order to skip the default behavior on the search page
     * and use the empty value instead.
     * 
     * @param  string  $key
     * @param  mixed  $value
     * @return mixed
     */
    protected function transform($key, $value) {
        if ($this->shouldExcludeRequest()) {
            return $value;
        }

        return parent::transform($key, $value);
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * The search request should be excluded, but there's no way
     * to get the underlying route name since request()->route()
     * returns null. Instead, the request's URL should be matched
     * against the defined value.
     * 
     * @return bool
     */
    private function shouldExcludeRequest(): bool {
        return preg_match('/\/tarsene/', request()->url());
    }
}
