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
        $essay = $this->repository->getBySlug($slug);
        $essay->addImpression();

        $data = [
            'essay'  => $essay,
            'seo'    => $essay,
            'images' => $essay->getImages(),
        ];

        return view('public.essays.view', $data);
    }

}
