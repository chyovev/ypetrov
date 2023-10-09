<?php

namespace App\Models;

use App\Models\Traits\HasVisitor;
use App\Notifications\NewContactMessage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    use HasFactory, HasVisitor;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int,string>
     */
    public $fillable = [
        'is_read', 'name', 'email', 'message',
    ];


    ///////////////////////////////////////////////////////////////////////////
    /**
     * Once a ContactMessage record gets created, an observer
     * listening for this event calls this method in order to
     * send the contact message as a notification to all
     * administrators.
     * 
     * NB! Keep in mind that the notification class implements
     *     the ShouldQueue interface, so instead of the email
     *     being sent straight away, it will be added to a queue
     *     which should be processed by a queue worker.
     * 
     * @see \App\Observers\ContactMessageObserver
     * @see \App\Notifications\NewContactMessage
     * 
     * @return void
     */
    public function sendAsNotification(): void {
        $users = User::getAllAdministrators();

        $users->each->notify((new NewContactMessage($this))->afterCommit());
    }
}
