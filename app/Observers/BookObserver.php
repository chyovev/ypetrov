<?php

namespace App\Observers;

use App\Models\Book;

class BookObserver
{

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Once a book has been saved, if the request contains any poem IDs
     * (and it should, the book's custom FormRequest makes sure of it),
     * said poems should be synced in the order of appearance.
     * 
     * NB! The only possible way for a request not to contain any poem IDs
     *     at all is during unit testing, hence the availability check.
     * 
     * @param  Book $book – a book being created/updated
     * @return void
     */
    public function saved(Book $book): void {
        $poemIds = request()->input('poem_id');

        if ($poemIds) {
            $book->syncPoemsInOrder($poemIds);
        }
    }

}
