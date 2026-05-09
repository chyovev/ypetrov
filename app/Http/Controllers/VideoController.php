<?php

namespace App\Http\Controllers;

use App\Models\Video;
use Illuminate\View\View;

class VideoController extends Controller
{

    ///////////////////////////////////////////////////////////////////////////
    public function view(Video $video): View {
        $videos = Video::orderBy('order')->with('attachments')->get();

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
