<?php

namespace App\Http\Controllers;

use App\Models\StaticPage;
use Illuminate\View\View;

class StaticPageController extends Controller
{

    ///////////////////////////////////////////////////////////////////////////
    public function home(): View {
        $page = StaticPage::getBiography();
        $page->addImpression();

        $data = [
            'page' => $page,
        ];

        return view('public.static_pages.view', $data);
    }

    ///////////////////////////////////////////////////////////////////////////
    public function chrestomathy(): View {
        $page = StaticPage::getChrestomathy();
        $page->addImpression();
        
        $data = [
            'page'   => $page,
            'seo'    => $page,
            'images' => $page->getImages(),
        ];

        return view('public.static_pages.view', $data);
    }

}
