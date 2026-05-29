<?php

namespace App\Models;

use App\Helpers\Contexter;
use App\Utils\Seo;
use App\Models\Interfaces\Commentable;
use App\Models\Interfaces\Statsable;
use App\Models\Interfaces\MetaData;
use App\Models\Scopes\ActiveScope;
use App\Models\Traits\HasComments;
use App\Models\Traits\HasStats;
use App\Observers\CommentableObserver;
use App\Observers\StatsableObserver;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

#[ScopedBy([ActiveScope::class])]
#[ObservedBy([CommentableObserver::class, StatsableObserver::class])]
#[Fillable(['is_active', 'title', 'slug', 'dedication', 'text', 'use_monospace_font'])]
class Poem extends Model implements Commentable, Statsable, MetaData
{
    use HasFactory;

    use HasComments;

    use HasStats;

    ///////////////////////////////////////////////////////////////////////////
    public function casts(): array {
        return [
            'is_active'          => 'boolean',
            'use_monospace_font' => 'boolean',
        ];
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * A book usually consists of multiple poems, and a single poem
     * can be part of multiple books. Therefore, a pivot many-to-many
     * table is used (book_poem) whose additional columns can also be
     * fetched using the withPivot() and withTimestamps() methods.
     * 
     * @return BelongstoMany
     */
    public function books(): BelongsToMany {
        return $this
            ->belongsToMany(Book::class)
            ->withPivot('order')
            ->orderBy('publish_year', 'asc')
            ->withTimestamps();
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * A poem can be commented on if it's marked as fully active.
     * 
     * @return bool
     */
    public function canBeCommentedOn(): bool {
        return $this->isFullyActive();
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * A poem can be liked if it's marked as fully active.
     * 
     * @return bool
     */
    public function canBeLiked(): bool {
        return $this->isFullyActive();
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * A poem is considered 'fully active' if it's marked as active
     * and has at least one book association (also active, global scope).
     */
    public function isFullyActive(): bool {
        return $this->is_active && $this->books()->exists();
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * When the user searches for a keyword of a poem, it would
     * make more sense for them to see the relevant context, i.e.
     * that part of the poem which contains said word.
     * 
     * @param  string $search – keyword
     * @return string
     */
    public function showSearchContext(string $search): string {
        $helper = new Contexter($this->text, $search);

        return $helper->extract();
    }

    ///////////////////////////////////////////////////////////////////////////
    public function getSeo(): Seo {
        return new Seo($this->title, $this->text);
    }

    ///////////////////////////////////////////////////////////////////////////
    public function scopeFilterBy(Builder $query, string $search): void {
        $search = "%{$search}%";

        $query->where('title', 'LIKE', $search)
            ->orWhere('text',  'LIKE', $search);
    }

}
