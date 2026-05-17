<?php

namespace App\Http\Controllers;

use App\Models\Essay;
use App\Models\Visitor;
use Illuminate\View\View;

class EssayController
{

    ///////////////////////////////////////////////////////////////////////////
    public function view(Essay $essay, Visitor $visitor): View {
        $essay->addImpression($visitor);

        $data = [
            'essay'  => $essay,
            'seo'    => $essay,
            'images' => $essay->getImages(),
        ];

        return view('public.essays.view', $data);
    }

}
