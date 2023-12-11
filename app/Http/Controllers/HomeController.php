<?php

namespace App\Http\Controllers;

use App\Repositories\StaticPageRepository;

class HomeController extends Controller
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
    public function index() {
        $data = [
            'page' => $this->repository->getBiography(),
        ];

        return view('public.home', $data);
    }

}
