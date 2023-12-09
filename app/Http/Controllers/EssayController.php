<?php

namespace App\Http\Controllers;

use App\Repositories\EssayRepository;

class EssayController extends Controller
{

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Dependency-inject all repositories needed by the controller.
     * Marking the parameters as private makes them available as
     * object properties.
     */
    public function __construct(private EssayRepository $repository) {
        //
    }
    
    ///////////////////////////////////////////////////////////////////////////
    public function view(string $slug) {
        $data = [
            'essay' => $this->repository->getBySlug($slug),
        ];

        return view('public.essays.view', $data);
    }

}