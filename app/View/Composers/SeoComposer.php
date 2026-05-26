<?php
 
namespace App\View\Composers;

use App\Utils\Seo;
use Illuminate\View\View;
 
class SeoComposer
{

    ///////////////////////////////////////////////////////////////////////////
    public function compose(View $view): void {
        // if the controller has not passed its own 'seo'
        // variable to the view, pass a default one
        if ( ! $view->offsetExists('seo')) {
            $view->with('seo', new Seo);
        }
    }
}