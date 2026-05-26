<?php

namespace App\Http\Controllers;

use App\Utils\Seo;

class ContactController
{

    ///////////////////////////////////////////////////////////////////////////
    public function index() {
        $data = [
            'seo' => new Seo('Контакт'),
        ];

        return view ('public.contact.index', $data);
    }
    
}
