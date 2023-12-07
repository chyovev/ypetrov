<?php

namespace App\Repositories;

use App\Models\Book;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;

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

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Fetch a single active book by its slug
     * together with its active poems.
     * 
     * @throws ModelNotFoundException – no such (active) book
     * @param  string $slug
     * @return self
     */
    public static function getBySlugWithPoems(string $slug): Book {
        return Book::query()
            ->where('slug', $slug)
            ->where('is_active', true)
            ->with([
                'poems' => function($q) {
                    $q->where('is_active', true);
                },
            ])
            ->firstOrFail();
    }
}