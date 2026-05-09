<?php

namespace App\Http\Controllers;

use App\Models\Video;
use App\Repositories\VideoRepository;
use Illuminate\View\View;

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
    public function view(Video $video): View {
        $videos = $this->repository->getAllActive();

        $video->addImpression();
        
        $data = [
            'videos' => $videos,
            'video'  => $video,
            'seo'    => $video,
            'images' => $video->getImages(),
        ];

        return view('public.videos.view', $data);
    }

}
