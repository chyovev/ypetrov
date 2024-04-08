<?php

namespace App\Http\Controllers;

use App\Repositories\BookRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class WorkController extends Controller
{

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Dependency-inject all repositories needed by the controller.
     * Marking the parameters as private makes them available as
     * object properties.
     */
    public function __construct(
        private BookRepository $bookRepository,
    ) {
        //
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Get a single active book (together with its active poems)
     * by its slug and pass it as a variable to the respective view.
     * 
     * @throws ModelNotFoundException – book not found or inactive
     * @param  string $bookSlug
     */
    public function get_book(string $bookSlug) {
        $book = $this->bookRepository->getBySlugWithPoems($bookSlug);
        $book->addImpression();

        $data = [
            'book' => $book,
        ];

        return view('public.works.book', $data);
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Poems are fetched via the books they're associated with,
     * i.e. both the book's and poem's slug should be present.
     * Since the book with all its poems should be fetched anyway
     * in order to populate the sidebar navigation, the target poem
     * can easily be extracted from the book's poem collection,
     * thus avoiding a redundant SQL query.
     * If said poem is missing from the collection, a not-found
     * exception will be thrown.
     * 
     * @param string $bookSlug
     * @param string $poemSlug
     */
    public function get_poem(string $bookSlug, string $poemSlug) {
        $book = $this->bookRepository->getBySlugWithPoems($bookSlug);
        $poem = $book->getPoemBySlug($poemSlug);
        $poem->addImpression();

        $data = [
            'book' => $book,
            'poem' => $poem,
        ];

        return view('public.works.poem', $data);
    }

}
