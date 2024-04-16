<?php

namespace App\Models;

use App\Helpers\Contexter;
use App\Models\Interfaces\Commentable;
use App\Models\Interfaces\Statsable;
use App\Models\Interfaces\SEO;
use App\Models\Traits\HasActiveState;
use App\Models\Traits\HasComments;
use App\Models\Traits\HasStats;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Poem extends Model implements Commentable, Statsable, SEO
{
    use HasFactory;

    /**
     * Add shortcut query builder method
     * to filter out inactive elements.
     */
    use HasActiveState;

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
        'is_active', 'title', 'slug', 'dedication', 'text', 'use_monospace_font',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string,string>
     */
    protected $casts = [
        'is_active'          => 'boolean',
        'use_monospace_font' => 'boolean',
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
     * A poem is considered 'fully active' if it's marked as active,
     * but if also at least one of the books it belongs to is also
     * marked as active.
     * 
     * @return bool
     */
    public function isFullyActive(): bool {
        return $this->isActive() && $this->hasActiveBook();
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Check if there's a book associated with the poem which is marked as active.
     * 
     * @return bool
     */
    public function hasActiveBook(): bool {
        return $this->books()->active()->exists();
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Local scope query to filter poems which have at least one active book.
     * 
     * @param  Builder $query – the query being prepared
     * @return Builder $query – query with appended book condition
     */
    public function scopeHasActiveBook(Builder $query): Builder {
        return $query->whereHas('books', function($query) {
            $query->active();
        });
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Local scope query to filter fully active poems.
     * 
     * @param  Builder $query – the query being prepared
     * @return Builder $query – query with appended condition
     */
    public function scopeFullyActive(Builder $query): Builder {
        return $query->active()->hasActiveBook();
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
    public function getMetaTitle(): ?string {
        return $this->title;
    }

    ///////////////////////////////////////////////////////////////////////////
    public function getMetaDescription(): ?string {
        return $this->text;
    }

}
