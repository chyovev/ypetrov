<?php

namespace App\Models;

use App\Models\Interfaces\Attachable;
use App\Models\Interfaces\Commentable;
use App\Models\Interfaces\SEO;
use App\Models\Interfaces\Statsable;
use App\Models\Scopes\ActiveScope;
use App\Models\Traits\HasAttachments;
use App\Models\Traits\HasComments;
use App\Models\Traits\HasStats;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

#[ScopedBy([ActiveScope::class])]
class PressArticle extends Model implements Attachable, Commentable, Statsable, SEO
{
    use HasFactory;

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
        'is_active', 'title', 'slug', 'press', 'publish_date', 'text', 'order',
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
    public function canBeCommentedOn(): bool {
        return $this->is_active;
    }

    ///////////////////////////////////////////////////////////////////////////
    public function canBeLiked(): bool {
        return $this->is_active;
    }

    ///////////////////////////////////////////////////////////////////////////
    public function getMetaTitle(): ?string {
        return $this->title;
    }

    ///////////////////////////////////////////////////////////////////////////
    public function getMetaDescription(): ?string {
        return $this->text;
    }

    ///////////////////////////////////////////////////////////////////////////
    public function scopeFilterBy(Builder $query, string $search): void {
        $search = "%{$search}%";

        $query->where('title', 'LIKE', $search)
            ->orWhere('press', 'LIKE', $search)
            ->orWhere('text',  'LIKE', $search);
    }

}
