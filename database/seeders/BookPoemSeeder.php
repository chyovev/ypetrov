<?php

namespace Database\Seeders;

use LogicException;
use App\Models\Book;
use App\Models\Poem;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Seeder;

/**
 * Unlike the Book and Poem seeders which simply
 * generate new records using the respective factories,
 * the BookPoem seeder simply connects already existing
 * records of both these models.
 * Therefore, it is a pre-condition to have both their
 * seeders executed beforehand, otherwise an exception
 * will be thrown. 
 * 
 * @see \Database\Seeders\DatabaseSeeder
 */

class BookPoemSeeder extends Seeder
{

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Fetch all books and poems and connect them in a random manner.
     * 
     * @return void
     */
    public function run(): void {
        $books = $this->fetchAllBooks();
        $poems = $this->fetchAllPoems();

        foreach ($books as $book) {
            $randomPoems = $this->extractRandomPoems($poems);

            $book->poems()->sync($randomPoems);
        }
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Fetch a collection of all existing books.
     * If no books are found, an exception will be thrown.
     * 
     * @throws LogicException – empty collection of books
     * @return Collection<Book>
     */
    private function fetchAllBooks(): Collection {
        $books = Book::all();

        if ( ! $books->count()) {
            throw new LogicException('Missing book records. Try running the book seeder first.');
        }

        return $books;
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Fetch a collection of all existing poems.
     * If no poems are found, an exception will be thrown.
     * 
     * @throws LogicException – empty collection of poems
     * @return Collection<Poem>
     */
    private function fetchAllPoems(): Collection {
        $poems = Poem::all();
        
        if ( ! $poems->count()) {
            throw new LogicException('Missing poem records. Try running the poem seeder first.');
        }
        
        return $poems;
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Out of the collection of all poems extract 10 random
     * ones, add an order pivot attribute for each of them
     * and return the result as an array having the structure:
     * 
     *     poem_id => ['pivot_attr' => 'pivot_value']
     * 
     * @return array<int,array<string,mixed>>
     */
    private function extractRandomPoems(Collection $poems): array {
        $random = $poems->random(10);
        $data   = [];

        // each poem associated with a book should
        // increment its order value in the pivot table
        foreach ($random as $key => $poem) {
            $data[$poem->id] = ['order' => $key + 1];
        }

        return $data;
    }

}
