<?php

namespace App\Http\Controllers;

use App\Utils\Seo;
use App\Models\GalleryImage;

class GalleryController
{
    
    ///////////////////////////////////////////////////////////////////////////
    public function index() {
        $data = [
            'gallery' => GalleryImage::orderBy('order')->with('attachments')->get(),
            'seo'     => new Seo('Галерия'),
        ];

        return view('public.gallery.index', $data);
    }

}
