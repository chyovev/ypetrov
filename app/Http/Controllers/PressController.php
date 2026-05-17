<?php

namespace App\Http\Controllers;

use App\Models\PressArticle;
use App\Models\Visitor;
use Illuminate\View\View;

class PressController
{

    ///////////////////////////////////////////////////////////////////////////
    public function view(PressArticle $article, Visitor $visitor): View {
        $article->addImpression($visitor);

        $data = [
            'article' => $article,
            'seo'     => $article,
            'images'  => $article->getImages(),
        ];

        return view('public.press.view', $data);
    }

}
