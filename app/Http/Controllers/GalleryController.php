<?php

namespace App\Http\Controllers;

use App\Repositories\GalleryImageRepository;

class GalleryController extends Controller
{

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Dependency-inject all repositories needed by the controller.
     * Marking the parameters as private makes them available as
     * object properties.
     */
    public function __construct(private GalleryImageRepository $repository) {
        //
    }
    
    ///////////////////////////////////////////////////////////////////////////
    public function index() {
        $data = [
            'images' => $this->repository->getAllActive(),
            'seo'    => seo('Галерия'),
        ];

        return view('public.gallery.index', $data);
    }

}
