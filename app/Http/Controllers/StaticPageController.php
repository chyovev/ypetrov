<?php

namespace App\Http\Controllers;

use App\Models\StaticPage;
use App\Models\Visitor;
use Illuminate\View\View;

class StaticPageController
{

    ///////////////////////////////////////////////////////////////////////////
    public function home(Visitor $visitor): View {
        $page = StaticPage::getBiography();
        $page->addImpression($visitor);

        $data = [
            'page' => $page,
        ];

        return view('public.static_pages.view', $data);
    }

    ///////////////////////////////////////////////////////////////////////////
    public function chrestomathy(Visitor $visitor): View {
        $page = StaticPage::getChrestomathy();
        $page->addImpression($visitor);
        
        $data = [
            'page'   => $page,
            'seo'    => $page,
            'images' => $page->getImages(),
        ];

        return view('public.static_pages.view', $data);
    }

}
