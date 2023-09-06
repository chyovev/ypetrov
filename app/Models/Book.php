<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Book extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int,string>
     */
    public $fillable = [
        'is_active', 'title', 'slug', 'publisher', 'publish_year', 'order',
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
            ->withTimestamps();
    }
}
