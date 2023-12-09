<?php

namespace App\Http\Controllers;

use App\Repositories\PressArticleRepository;

class PressController extends Controller
{

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Dependency-inject all repositories needed by the controller.
     * Marking the parameters as private makes them available as
     * object properties.
     */
    public function __construct(private PressArticleRepository $repository) {
        //
    }
    
    ///////////////////////////////////////////////////////////////////////////
    public function view(string $slug) {
        $data = [
            'article' => $this->repository->getBySlug($slug),
        ];

        return view('public.press.view', $data);
    }

}