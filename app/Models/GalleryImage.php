<?php

namespace App\Models;

use App\Models\Interfaces\Attachable;
use App\Models\Interfaces\Statsable;
use App\Models\Scopes\ActiveScope;
use App\Models\Traits\HasAttachments;
use App\Models\Traits\HasStats;
use App\Observers\AttachableObserver;
use App\Observers\StatsableObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

#[ScopedBy([ActiveScope::class])]
#[ObservedBy([AttachableObserver::class, StatsableObserver::class])]
class GalleryImage extends Model implements Attachable, Statsable
{
    use HasFactory;

    use HasAttachments;

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
    public function canBeLiked(): bool {
        return $this->is_active;
    }

    ///////////////////////////////////////////////////////////////////////////
    public function scopeFilterBy(Builder $query, string $search): void {
        $search = "%{$search}%";

        $query->where('title', 'LIKE', $search);
    }

}
