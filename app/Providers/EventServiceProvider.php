<?php

namespace App\Providers;

use App\Models\Attachment;
use App\Models\ContactMessage;
use App\Events\CommentCreated;
use App\Events\ContactMessageCreated;
use App\Listeners\SendCommentNotification;
use App\Listeners\SendContactMessageNotification;
use App\Observers\AttachmentObserver;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{

    /**
     * The model observers used by the application.
     *
     * NB! Polymorphic observers are registered the first time
     *     any of the respective main objects gets initialized.
     *     
     * @see \App\Models\Traits\HasAttachments  :: bootHasAttachments()
     * @see \App\Models\Traits\HasComments     :: bootHasComments()
     * @see \App\Models\Traits\HasStats        :: bootHasStats()
     *
     * @var array
     */
    protected $observers = [
        Attachment::class => [AttachmentObserver::class],
    ];

    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        CommentCreated::class => [
            SendCommentNotification::class,
        ],
        ContactMessageCreated::class => [
            SendContactMessageNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
