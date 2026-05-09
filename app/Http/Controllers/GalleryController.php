<?php

namespace App\Http\Controllers;

use App\Models\GalleryImage;

class GalleryController extends Controller
{
    
    ///////////////////////////////////////////////////////////////////////////
    public function index() {
        $data = [
            'gallery' => GalleryImage::orderBy('order')->with('attachments')->get(),
            'seo'     => seo('Галерия'),
        ];

        return view('public.gallery.index', $data);
    }

}
