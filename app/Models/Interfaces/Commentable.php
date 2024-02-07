<?php

namespace App\Models\Interfaces;

use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * All models which have a polymorphic relationship to
 * the Comment model should implement this interface
 * and use the hasComments trait.
 * Commentable models have an observer which takes care
 * of polymorphic data clean-up during deletion.
 * 
 * @see \App\Models\Traits\HasComments 
 * @see \App\Observers\CommentableObserver
 */

interface Commentable extends Interactive
{

    ///////////////////////////////////////////////////////////////////////////
    /**
     * All models implementing the Commentable interface should
     * have a polymorphic relationship to the Comment model declared.
     * 
     * @return MorphMany
     */
    public function comments(): MorphMany;
}