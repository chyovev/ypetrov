<?php

namespace Tests\Feature\Event;

use Tests\TestCase;
use App\Models\Book;
use App\Models\Visitor;
use App\Events\CommentCreated;
use App\Listeners\SendCommentNotification;
use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CommentCreatedTest extends TestCase
{

    /**
     * Wrap each test in a transaction so that
     * data is not persisted to the database.
     */
    use DatabaseTransactions;


    ///////////////////////////////////////////////////////////////////////////
    public function test_event_is_fired(): void {
        $visitor = Visitor::factory()->create();
        $book    = Book::factory()->create();
        $name    = 'John Smith';
        $message = 'Test';

        Event::fake();
        
        $book->addComment($visitor, $name, $message);

        Event::assertDispatched(CommentCreated::class);
    }

    ///////////////////////////////////////////////////////////////////////////
    public function test_event_has_listener(): void {
        Event::fake();
        
        Event::assertListening(
            CommentCreated::class,
            SendCommentNotification::class,
        );
    }

}
