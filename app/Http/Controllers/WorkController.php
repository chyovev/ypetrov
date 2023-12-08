<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Repositories\BookRepository;
use Illuminate\Http\Response;
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
        $data = [
            'book' => $this->bookRepository->getBySlugWithPoems($bookSlug),
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
     * 
     * @param string $bookSlug
     * @param string $poemSlug
     */
    public function get_poem(string $bookSlug, string $poemSlug) {
        /** @var Book <-- intelephense is confused */
        $book = $this->bookRepository->getBySlugWithPoems($bookSlug);
        $poem = $book->getPoemBySlug($poemSlug);

        // if the poem is not associated with the book
        // or is marked as inactive, return a 404 result
        if ( ! $poem) {
            abort(Response::HTTP_NOT_FOUND);
        }
        
        $data = [
            'book' => $book,
            'poem' => $poem,
        ];

        return view('public.works.poem', $data);
    }

}
