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
        $article = $this->repository->getBySlug($slug);
        $article->addImpression();

        $data = [
            'article' => $article,
            'seo'     => $article,
        ];

        return view('public.press.view', $data);
    }

}
