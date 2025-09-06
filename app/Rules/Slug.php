<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class Slug implements ValidationRule
{

    ///////////////////////////////////////////////////////////////////////////
    public function validate(string $attribute, mixed $value, Closure $fail): void {
        // only lowercase letters, numbers, hyphens
        $pattern = '/^[a-z0-9\-]+$/';

        if (preg_match($pattern, $value) === 0) {
            $fail('validation.slug')->translate();
        }
    }
}
