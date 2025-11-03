<?php

namespace App\Listeners;

use App\Models\User;
use App\Events\ContactMessageCreated;
use App\Notifications\ContactMessageNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueueAfterCommit;
use Illuminate\Database\Eloquent\Collection;

class SendContactMessageNotification implements ShouldQueueAfterCommit
{

    use Queueable;

    ///////////////////////////////////////////////////////////////////////////
    public function handle(ContactMessageCreated $event): void {
        $message = $event->message;
        $users   = $this->getRecipients();
        
        $users->each(function(User $user) use($message): void {
            $user->notify(new ContactMessageNotification($message));
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
