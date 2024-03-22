<?php

namespace App\Http\Controllers;

use App\Repositories\StaticPageRepository;

class StaticPageController extends Controller
{

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Dependency-inject all repositories needed by the controller.
     * Marking the parameters as private makes them available as
     * object properties.
     */
    public function __construct(private StaticPageRepository $repository) {
        // 
    }

    ///////////////////////////////////////////////////////////////////////////
    public function home() {
        $page = $this->repository->getBiography();
        $page->addImpression();

        $data = [
            'page' => $page,
        ];

        return view('public.static_pages.view', $data);
    }

    ///////////////////////////////////////////////////////////////////////////
    public function chrestomathy() {
        $page = $this->repository->getChrestomathy();
        $page->addImpression();
        
        $data = [
            'page' => $page,
        ];

        return view('public.static_pages.view', $data);
    }

}
