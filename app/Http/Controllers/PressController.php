<?php

namespace App\Http\Controllers;

use App\Models\PressArticle;
use Illuminate\View\View;

class PressController
{

    ///////////////////////////////////////////////////////////////////////////
    public function view(PressArticle $article): View {
        $article->addImpression();

        $data = [
            'article' => $article,
            'seo'     => $article,
            'images'  => $article->getImages(),
        ];

        return view('public.press.view', $data);
    }

}
