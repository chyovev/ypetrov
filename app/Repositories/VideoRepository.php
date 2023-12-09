<?php

namespace App\Repositories;

use App\Models\Video;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;

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

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Get a single active video by its slug.
     * 
     * @throws ModelNotFoundException
     * @return Video
     */
    public static function getBySlug(string $slug): Video {
        return Video::query()
            ->active()
            ->where('slug', $slug)
            ->with('attachments')
            ->firstOrFail();
    }

}