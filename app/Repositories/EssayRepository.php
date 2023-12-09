<?php

namespace App\Repositories;

use App\Models\Essay;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;

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

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Get a single active essay by its slug.
     * 
     * @throws ModelNotFoundException
     * @return Collection<Essay>
     */
    public static function getBySlug(string $slug): Essay {
        return Essay::query()
            ->active()
            ->where('slug', $slug)
            ->firstOrFail();
    }

}