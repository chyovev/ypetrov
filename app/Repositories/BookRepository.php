<?php

namespace App\Repositories;

use App\Models\Book;
use Illuminate\Database\Eloquent\Collection;

class BookRepository
{

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Get all active books without any associations.
     * 
     * @return Collection<Book>
     */
    public static function getAllActive(): Collection {
        return Book::query()
            ->where('is_active', true)
            ->orderBy('order')
            ->get();
    }

}