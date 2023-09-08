<?php

namespace App\Models;

use App\Notifications\NewContactMessage;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int,string>
     */
    public $fillable = [
        'is_read', 'name', 'email', 'message', 'ip_hash',
    ];

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Anytime a contact message record gets created, the IP address
     * of the user gets stored as well in order to check against it
     * and block potential consecutive spam requests.
     * To comply with GDPR regulations, the IP address gets hashed
     * automatically during mass assignment using the ipHash mutator.
     * The algorithm used is sha256 which always returns a value of
     * 64 characters – even if there is an insignificant chance for
     * a collision, the purpose of the hashing makes it worth the “risk”.
     * 
     * @return Attribute
     */
    protected function ipHash(): Attribute {
        return Attribute::make(
            set: fn (string $value) => hash('sha256', $value),
        );
    }

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
