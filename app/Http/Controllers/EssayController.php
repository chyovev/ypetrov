<?php

namespace App\Http\Controllers;

use App\Models\Essay;
use Illuminate\View\View;

class EssayController
{

    ///////////////////////////////////////////////////////////////////////////
    public function view(Essay $essay): View {
        $essay->addImpression();

        $data = [
            'essay'  => $essay,
            'seo'    => $essay,
            'images' => $essay->getImages(),
        ];

        return view('public.essays.view', $data);
    }

}
