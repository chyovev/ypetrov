<?php

namespace App\Repositories;

use App\Models\PressArticle;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PressArticleRepository
{

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Get all active press articles.
     * 
     * @return Collection<PressArticle>
     */
    public static function getAllActive(): Collection {
        return PressArticle::query()
            ->active()
            ->orderBy('order')
            ->get();
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Get a single active press article by its slug.
     * 
     * @throws ModelNotFoundException
     * @return Collection<PressArticle>
     */
    public static function getBySlug(string $slug): PressArticle {
        return PressArticle::query()
            ->active()
            ->where('slug', $slug)
            ->firstOrFail();
    }

}