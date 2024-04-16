<?php

namespace App\Http\Controllers;

class ContactController extends Controller
{

    ///////////////////////////////////////////////////////////////////////////
    public function index() {
        $data = [
            'seo' => seo('Контакт'),
        ];

        return view ('public.contact.index', $data);
    }
    
}
