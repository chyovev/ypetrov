<?php

namespace App\Http\Controllers;

use App\Repositories\VideoRepository;

class VideoController extends Controller
{
    
    ///////////////////////////////////////////////////////////////////////////
    /**
     * Dependency-inject all repositories needed by the controller.
     * Marking the parameters as private makes them available as
     * object properties.
     */
    public function __construct(private VideoRepository $repository) {
        // 
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Get a single active video by its slug.
     * The view video page should list all videos in the sidebar
     * navigation, but there's no need to fetch them here since
     * they will be fetched by the navigation composer right before
     * the view gets rendered.
     * 
     * @see \App\View\Composers\NavigationComposer
     * 
     * @param string $slug
     */
    public function view(string $slug) {
        $video = $this->repository->getBySlug($slug);
        $video->addImpression();
        
        $data = [
            'video' => $video,
        ];

        return view('public.videos.view', $data);
    }
    
}
