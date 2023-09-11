<?php

namespace App\Providers;

use App\Models\Comment;
use App\Models\ContactMessage;
use App\Observers\CommentObserver;
use App\Observers\ContactMessageObserver;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{

    /**
     * The model observers used by the application.
     *
     * NB! Polymorphic observers are registered the first time
     *     any of the respective main objects gets initialized.
     *     
     * @see \App\Models\Traits\CustomHasEvents :: registerObserverToModel()
     *
     * @var array
     */
    protected $observers = [
        ContactMessage::class => [ContactMessageObserver::class],
        Comment::class        => [CommentObserver::class],
    ];

    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
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
