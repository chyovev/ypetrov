<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsInboundAttributes;
use Illuminate\Database\Eloquent\Model;

class Sha256Cast implements CastsInboundAttributes
{

    ///////////////////////////////////////////////////////////////////////////
    public function set(Model $model, string $key, mixed $value, array $attributes): mixed {
        return hash('sha256', $value);
    }
}
