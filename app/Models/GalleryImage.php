<?php

namespace App\Models;

use App\Models\Interfaces\Attachable;
use App\Models\Interfaces\Statsable;
use App\Models\Traits\HasActiveState;
use App\Models\Traits\HasAttachments;
use App\Models\Traits\HasStats;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GalleryImage extends Model implements Attachable, Statsable
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
     * The HasStats trait defines a polymorphic
     * relationship to the Stats model and registers
     * a delete-event observer.
     * 
     * NB! The impression counter has not been activated
     *     on gallery images since all records are loaded
     *     at once.
     */
    use HasStats;

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

    ///////////////////////////////////////////////////////////////////////////
    /**
     * A gallery image can be liked if it's marked as active.
     * 
     * @return bool
     */
    public function canBeLiked(): bool {
        return $this->isActive();
    }


}
