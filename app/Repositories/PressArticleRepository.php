<?php

namespace App\Repositories;

use App\Models\PressArticle;
use Illuminate\Database\Eloquent\Collection;

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

}