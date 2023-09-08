<?php

namespace App\Observers;

use App\Models\ContactMessage;

class ContactMessageObserver
{

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Every time a contact message record gets created,
     * a notification email should be sent to all users
     * (administrators) to notify them about the event.
     * 
     * NB! Keep in mind that this event is not being fired
     *     during seeding due to the WithoutModelEvents trait.
     * 
     * @param  ContactMessage $contactMessage – newly created record's object
     * @return void
     */
    public function created(ContactMessage $contactMessage): void {
        $contactMessage->sendAsNotification();
    }

}
