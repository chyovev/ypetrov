<?php

namespace App\Observers;

use App\Models\Interfaces\Commentable;

/**
 * The CommentableObserver is used on all models which
 * implement the Commentable interface.
 * Its goal is to delete all associated comments once
 * the main object is deleted since it cannot be done
 * on polymorphic tables using cascade delete in the
 * database.
 * 
 * NB! Keep in mind the observer is registered upon object
 *     initialization, and NOT in the EventServiceProvider.
 * 
 * @see \App\Models\Traits\HasComments :: initializeHasComments()
 */

class CommentableObserver
{

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Once a commentable object gets deleted, all its comments
     * from the polymorphic relationship should be gone, too.
     * 
     * @param  Commentable $object – object implementing the Commentable interface
     * @return void
     */
    public function deleted(Commentable $object): void {
        // make sure the commentable object is *really* gone:
        // if it's being soft deleted, its $exists property
        // will remain true
        if ( ! $object->exists) {
            // since comments can be deleted “softly”,
            // use a forceDelete() to purge them
            $object->comments()->forceDelete();
        }
    }

}
