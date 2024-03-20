<?php

namespace App\Models;

use App\Models\Interfaces\Commentable;
use App\Models\Interfaces\Statsable;
use App\Models\Traits\HasActiveState;
use App\Models\Traits\HasComments;
use App\Models\Traits\HasStats;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Poem extends Model implements Commentable, Statsable
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
        'use_monospace_font' => 'datetime',
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
     * A poem can be commented on if it's marked as active
     * AND if at least one of the books it belongs to is
     * also marked as active.
     * 
     * @return bool
     */
    public function canBeCommentedOn(): bool {
        return $this->isActive() && $this->books()->active()->exists();
    }

}
