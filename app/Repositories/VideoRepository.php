<?php

namespace App\Repositories;

use App\Models\Video;
use Illuminate\Database\Eloquent\Collection;

class VideoRepository
{

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Get all active videos with their attachments.
     * 
     * @return Collection<Video>
     */
    public static function getAllActive(): Collection {
        return Video::query()
            ->active()
            ->with('attachments')
            ->orderBy('order')
            ->get();
    }

}