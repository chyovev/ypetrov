<?php

namespace App\Models\Traits;

use App\Models\Comment;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * The HasComments trait is supposed to be used on models
 * which have a polymorphic relationship to the Comment model.
 */

trait HasComments
{

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Each commentable object can have multiple comments
     * and all comments are stored in a polymorphic table.
     * 
     * @return MorphMany
     */
    public function comments(): MorphMany {
        return $this->morphMany(Comment::class, 'commentable');
    }

}