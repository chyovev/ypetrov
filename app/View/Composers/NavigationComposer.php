<?php
 
namespace App\View\Composers;

use App\Repositories\BookRepository;
use App\Repositories\EssayRepository;
use Illuminate\View\View;
 
class NavigationComposer
{

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Marking constructor parameters as private makes
     * them available as object properties.
     */
    public function __construct(
        private BookRepository  $bookRepository,
        private EssayRepository $essayRepository,
    ) {
        //
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Pass variables to all views associated with current view composer.
     */
    public function compose(View $view): void {
        $view->with([
            'books'  => $this->getBooks(),
            'essays' => $this->getEssays(),
        ]);
    }

    ///////////////////////////////////////////////////////////////////////////
    private function getBooks() {
        return $this->bookRepository->getAllActive();
    }

    ///////////////////////////////////////////////////////////////////////////
    private function getEssays() {
        return $this->essayRepository->getAllActive();
    }
}