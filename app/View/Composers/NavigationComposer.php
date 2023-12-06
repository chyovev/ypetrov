<?php
 
namespace App\View\Composers;

use App\Repositories\BookRepository;
use Illuminate\View\View;
 
class NavigationComposer
{

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Marking constructor parameters as protected makes
     * them available as object properties.
     */
    public function __construct(
        protected BookRepository $bookRepository
    ) {
        //
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Pass variables to all views associated with current view composer.
     */
    public function compose(View $view): void {
        $view->with([
            'books' => $this->getBooks(),
        ]);
    }

    ///////////////////////////////////////////////////////////////////////////
    private function getBooks() {
        return $this->bookRepository->getAllActive();
    }
}