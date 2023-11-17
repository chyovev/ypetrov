<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\TrimStrings as Middleware;

class TrimStrings extends Middleware
{
    /**
     * The names of the attributes that should not be trimmed.
     *
     * @var array<int, string>
     */
    protected $except = [
        'current_password',
        'password',
        'password_confirmation',
    ];


    ///////////////////////////////////////////////////////////////////////////
    /**
     * The TrimString's parent transform() method takes care of
     * trimming each request's fields. Some poems make use of
     * leading white-spaces (for visual text alignment), so the
     * text field of the Poem model should be excluded from
     * trimming. However, Laravel offers no option to exclude
     * single fields per model – it's either all or none.
     * To work around this, the transfom method is overwritten
     * in order to skip such fields from trimming by keeping
     * their original value.
     * 
     * @param  string  $key
     * @param  mixed  $value
     * @return mixed
     */
    protected function transform($key, $value) {
        if ($this->shouldSkipAttributeFromTrimming($key)) {
            return $value;
        }

        return parent::transform($key, $value);
    }

    ///////////////////////////////////////////////////////////////////////////
    private function shouldSkipAttributeFromTrimming(string $field): bool {
        $isPoems = preg_match('/\/poems\//', request()->url());

        return ($isPoems && $field === 'text');
    }
}
