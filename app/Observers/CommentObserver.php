<?php

namespace App\Observers;

use App\Models\Comment;

class CommentObserver
{

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Every time a comment record gets created,
     * a notification email should be sent to all users
     * (administrators) to notify them about the event.
     * 
     * NB! Keep in mind that this event is not being fired
     *     during seeding due to the WithoutModelEvents trait.
     * 
     * @param  Comment $comment – newly created record's object
     * @return void
     */
    public function created(Comment $comment): void {
        $comment->sendAsNotification();
    }

}
