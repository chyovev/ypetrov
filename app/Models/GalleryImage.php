<?php

namespace App\Models;

use App\Models\Interfaces\Attachable;
use App\Models\Traits\HasActiveState;
use App\Models\Traits\HasAttachments;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GalleryImage extends Model implements Attachable
{
    use HasFactory;

    /**
     * Add shortcut query builder method
     * to filter out inactive elements.
     */
    use HasActiveState;

    /**
     * The HasAttachments trait defines a polymorphic
     * relationship to the Attachment model and registers
     * a delete-event observer.
     */
    use HasAttachments;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int,string>
     */
    public $fillable = [
        'is_active', 'title', 'order',
    ];

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Use the URL of the first attachment of image type as a gallery
     * image URL. Keep in mind that there might be no such item, so
     * the URL will end up being null.
     * 
     * @return string|null
     */
    public function getImageURL(): ?string {
        return $this->getFirstImage()?->getURL();
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Get the first image attachment and use its thumb's URL
     * as a gallery image's URL.
     * 
     * @return string|null
     */
    public function getImageThumbURL(): ?string {
        return $this->getFirstImage()?->getThumbURL();
    }

}
