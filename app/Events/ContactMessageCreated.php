<?php

namespace App\Events;

use App\Models\ContactMessage;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;

class ContactMessageCreated
{
    use Dispatchable, SerializesModels;

    ///////////////////////////////////////////////////////////////////////////
    public function __construct(public ContactMessage $message) {
        //
    }

}
