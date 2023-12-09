<?php

namespace App\Repositories;

use App\Models\GalleryImage;
use Illuminate\Database\Eloquent\Collection;

class GalleryImageRepository
{

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Get all active gallery images together with their attachments.
     * 
     * @return Collection<GalleryImage>
     */
    public static function getAllActive(): Collection {
        return GalleryImage::query()
            ->active()
            ->with('attachments')
            ->orderBy('order')
            ->get();
    }

}