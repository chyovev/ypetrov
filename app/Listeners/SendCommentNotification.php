<?php

namespace App\Listeners;

use App\Models\User;
use App\Events\CommentCreated;
use App\Notifications\CommentNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueueAfterCommit;
use Illuminate\Database\Eloquent\Collection;

class SendCommentNotification implements ShouldQueueAfterCommit
{

    use Queueable;

    ///////////////////////////////////////////////////////////////////////////
    public function handle(CommentCreated $event): void {
        $comment = $event->comment;
        $users   = $this->getRecipients();
        
        $users->each(function(User $user) use($comment): void {
            $user->notify(new CommentNotification($comment));
        });
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * @return Collection<User>
     */
    private function getRecipients(): Collection {
        return User::all();
    }

}
