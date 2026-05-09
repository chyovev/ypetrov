<?php
 
namespace App\View\Composers;

use App\Models\Book;
use App\Models\Essay;
use App\Models\PressArticle;
use App\Models\Video;
use Illuminate\View\View;
 
class NavigationComposer
{

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Pass variables to all views associated with current view composer.
     */
    public function compose(View $view): void {
        $view->with([
            'books'  => Book::orderBy('order')->get(),
            'essays' => Essay::orderBy('order')->get(),
            'press'  => PressArticle::orderBy('order')->get(),
            'videos' => Video::orderBy('order')->get(),
        ]);
    }
}