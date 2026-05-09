<?php

namespace App\Repositories;

use App\Models\Essay;
use Illuminate\Database\Eloquent\Collection;

class EssayRepository
{

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Get all active essays.
     * 
     * @return Collection<Essay>
     */
    public static function getAllActive(): Collection {
        return Essay::query()
            ->active()
            ->orderBy('order')
            ->get();
    }

}