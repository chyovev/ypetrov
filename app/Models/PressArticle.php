<?php

namespace App\Models;

use App\Utils\Seo;
use App\Models\Interfaces\Attachable;
use App\Models\Interfaces\Commentable;
use App\Models\Interfaces\MetaData;
use App\Models\Interfaces\Statsable;
use App\Models\Scopes\ActiveScope;
use App\Models\Traits\HasAttachments;
use App\Models\Traits\HasComments;
use App\Models\Traits\HasStats;
use App\Observers\AttachableObserver;
use App\Observers\CommentableObserver;
use App\Observers\StatsableObserver;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

#[ScopedBy([ActiveScope::class])]
#[ObservedBy([CommentableObserver::class, AttachableObserver::class, StatsableObserver::class])]
#[Fillable(['is_active', 'title', 'slug', 'press', 'publish_date', 'text', 'order'])]
class PressArticle extends Model implements Attachable, Commentable, Statsable, MetaData
{
    use HasFactory;

    use HasAttachments;

    use HasComments;

    use HasStats;

    ///////////////////////////////////////////////////////////////////////////
    public function casts(): array {
        return [
            'is_active'    => 'boolean',
            'publish_date' => 'datetime',
        ];
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
    public function getSeo(): Seo {
        return new Seo($this->title, $this->text);
    }

    ///////////////////////////////////////////////////////////////////////////
    public function scopeFilterBy(Builder $query, string $search): void {
        $search = "%{$search}%";

        $query->where('title', 'LIKE', $search)
            ->orWhere('press', 'LIKE', $search)
            ->orWhere('text',  'LIKE', $search);
    }

}
