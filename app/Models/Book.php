<?php

namespace App\Models;

use App\Models\Interfaces\Attachable;
use App\Models\Interfaces\Commentable;
use App\Models\Traits\HasAttachments;
use App\Models\Traits\HasComments;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Book extends Model implements Attachable, Commentable
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
