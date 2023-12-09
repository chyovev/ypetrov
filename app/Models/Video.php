<?php

namespace App\Models;

use App\Models\Interfaces\Attachable;
use App\Models\Interfaces\Commentable;
use App\Models\Interfaces\Statsable;
use App\Models\Traits\HasActiveState;
use App\Models\Traits\HasAttachments;
use App\Models\Traits\HasComments;
use App\Models\Traits\HasStats;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Video extends Model implements Attachable, Commentable, Statsable
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
     * The HasComments trait defines a polymorphic
     * relationship to the Comment model and registers
     * a delete-event observer.
     */
    use HasComments;

    /**
     * The HasStats trait defines a polymorphic
     * relationship to the Stats model and registers
     * a delete-event observer.
     */
    use HasStats;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int,string>
     */
    public $fillable = [
        'is_active', 'title', 'slug', 'publish_date', 'summary', 'order',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string,string>
     */
    protected $casts = [
        'is_active'    => 'boolean',
        'publish_date' => 'datetime',
    ];

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Normally each video should have a static image which
     * will be used as a video cover.
     * 
     * @return string|null
     */
    public function getCoverImage(): ?string {
        return $this->getFirstImage()?->getURL();
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * From all the attachments of a video filter the ones
     * which are actually of video MIME type.
     * 
     * @return Collection
     */
    public function getVideos(): Collection {
        $videos = $this->getAttachmentsByType('video');

        // if there is an mp4 attachment, move it to the top
        // as it offers greater compatibility and video quality
        return $videos->sort(function(Attachment $attachment) {
            return ( ! $attachment->hasType('mp4'));
        });
    }

}
