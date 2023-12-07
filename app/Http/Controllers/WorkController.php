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
        $data = [
            'book' => $this->bookRepository->getBySlugWithPoems($bookSlug),
        ];

        return view('public.works.book', $data);
    }

}
