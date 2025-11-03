<?php

namespace Tests\Feature\Event;

use Tests\TestCase;
use App\Events\ContactMessageCreated;
use App\Listeners\SendContactMessageNotification;
use Illuminate\Support\Facades\Event;

class ContactMessageCreatedTest extends TestCase
{

    ///////////////////////////////////////////////////////////////////////////
    public function test_event_has_listener(): void {
        Event::fake();
        
        Event::assertListening(
            ContactMessageCreated::class,
            SendContactMessageNotification::class,
        );
    }

}
