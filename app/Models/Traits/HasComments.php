<?php

namespace App\Models\Traits;

use LogicException;
use App\Models\Comment;
use App\Models\Interfaces\Commentable;
use App\Observers\CommentableObserver;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * The HasComments trait is supposed to be used on models
 * which have a polymorphic relationship to the Comment model.
 * To be used together with the Commentable interface.
 * 
 * @see \App\Models\Interfaces\Commentable
 */

trait HasComments
{

    use CustomHasEvents;

    use IsInteractive;

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Each commentable object can have multiple comments
     * and all comments are stored in a polymorphic table.
     * When fetched, by default the comments are ordered
     * from newest to oldest in a descending order.
     * 
     * @return MorphMany
     */
    public function comments(): MorphMany {
        return $this
            ->morphMany(Comment::class, 'commentable')
            ->latest('id');
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Since there's no way to register an observer on all models
     * implementing a certain interface in the event service provider,
     * a work-around is to register it upon trait initialization.
     * 
     * NB! Traits are initialized automatically in the models'
     *     constructors.
     * 
     * NB! Both validation and registration methods are declared
     *     in the CustomHasEvents trait.
     * 
     * @see \Illuminate\Database\Eloquent\Model :: initializeTraits()
     * @see \App\Models\Traits\CustomHasEvents
     * 
     * @throws LogicException – class not implementing required interface
     * @return void
     */
    public function initializeHasComments(): void {
        $this->validateModelImplementsInterface(Commentable::class);

        $this->registerObserverToModel(CommentableObserver::class);
    }

}