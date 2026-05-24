<?php

namespace App\Models;

use App\Models\Interfaces\Attachable;
use App\Models\Interfaces\Commentable;
use App\Models\Interfaces\Statsable;
use App\Models\Interfaces\SEO;
use App\Models\Scopes\ActiveScope;
use App\Models\Traits\HasAttachments;
use App\Models\Traits\HasComments;
use App\Models\Traits\HasStats;
use App\Observers\AttachableObserver;
use App\Observers\CommentableObserver;
use App\Observers\StatsableObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

#[ScopedBy([ActiveScope::class])]
#[ObservedBy([CommentableObserver::class, AttachableObserver::class, StatsableObserver::class])]
class Book extends Model implements Attachable, Commentable, Statsable, SEO
{
    use HasFactory;

    use HasAttachments;

    use HasComments;

    use HasStats;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int,string>
     */
    public $fillable = [
        'is_active', 'title', 'slug', 'text', 'publisher', 'publish_year', 'order',
    ];

    ///////////////////////////////////////////////////////////////////////////
    /**
     * A book usually consists of multiple poems, and a single poem
     * can be part of multiple books. Therefore, a pivot many-to-many
     * table is used (book_poem) whose additional columns can also be
     * fetched using the withPivot() and withTimestamps() methods.
     * 
     * @return BelongstoMany
     */
    public function poems(): BelongsToMany {
        return $this
            ->belongsToMany(Poem::class)
            ->withPivot('order')
            ->orderByPivot('order')
            ->withTimestamps();
    }

    ///////////////////////////////////////////////////////////////////////////
    public function canBeCommentedOn(): bool {
        return $this->is_active;
    }

    ///////////////////////////////////////////////////////////////////////////
    public function canBeLiked(): bool {
        return $this->is_active;
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Take the first image from the book's attachments
     * and use it as a cover (if uploaded).
     * 
     * @return string|null
     */
    public function getCoverImage(): ?string {
        return $this->getFirstImage()?->getURL();
    }

    ///////////////////////////////////////////////////////////////////////////
    public function getMetaTitle(): ?string {
        $title = $this->title;

        if ($this->publish_year) {
            $title .= " ({$this->publish_year})";
        }

        return $title;
    }

    ///////////////////////////////////////////////////////////////////////////
    public function getMetaDescription():? string {
        if ($this->text) {
            return $this->text;
        }

        $description = $this->getMetaTitle();

        if ($this->publisher) {
            $description .= ", издателство {$this->publisher}";
        }

        return $description;
    }

    ///////////////////////////////////////////////////////////////////////////
    public function scopeFilterBy(Builder $query, string $search): void {
        $search = "%{$search}%";

        $query->where('title',        'LIKE', $search)
            ->orWhere('publisher',    'LIKE', $search)
            ->orWhere('publish_year', 'LIKE', $search);
    }

}
