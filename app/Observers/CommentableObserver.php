<?php

namespace App\Observers;

use App\Models\Interfaces\Commentable;

class CommentableObserver
{

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Once a commentable object gets deleted, all its comments
     * from the polymorphic relationship should be gone, too.
     */
    public function deleted(Commentable $commentable): void {
        // since comments can be deleted “softly”,
        // use a forceDelete() to purge them
        $commentable->comments()->forceDelete();
    }

}
