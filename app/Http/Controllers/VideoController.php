<?php

namespace App\Http\Controllers;

use App\Models\Video;
use App\Models\Visitor;
use Illuminate\View\View;

class VideoController
{

    ///////////////////////////////////////////////////////////////////////////
    public function view(Video $video, Visitor $visitor): View {
        $videos = Video::orderBy('order')->with('attachments')->get();

        $video->addImpression($visitor);
        
        $data = [
            'videos' => $videos,
            'video'  => $video,
            'seo'    => $video->getSeo(),
            'images' => $video->getImages(),
        ];

        return view('public.videos.view', $data);
    }

}
