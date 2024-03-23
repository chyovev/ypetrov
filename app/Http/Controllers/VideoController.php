<?php

namespace App\Http\Controllers;

use App\Models\Video;
use App\Repositories\VideoRepository;
use Illuminate\Support\ItemNotFoundException;
use Illuminate\Database\Eloquent\Collection;

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
     * The view video page should list all videos in the sidebar
     * navigation, as well as the main video. To avoid an additional
     * SQL request, it's better to fetch all videos as a collection
     * and try to find the matching main video in it.
     * 
     * @param string $slug
     */
    public function view(string $slug) {
        $videos = $this->repository->getAllActive();

        $video = $this->getVideoBySlug($videos, $slug);
        $video->addImpression();
        
        $data = [
            'videos' => $videos,
            'video'  => $video,
        ];

        return view('public.videos.view', $data);
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Cycle through all videos and try to find an element
     * which matches the slug passed as parameter.
     * 
     * @throws ItemNotFoundException
     * @param  Collection<Video> $videos
     * @param  string $slug
     * @return Video
     */
    private function getVideoBySlug(Collection $videos, string $slug): Video {
        return $videos->filter(function(Video $video) use($slug) {
            return ($video->slug === $slug) ;
        })->firstOrFail();
    }
    
}
