<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Essay;
use App\Models\Visitor;
use App\Events\CommentCreated;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Event;

class CommentableTest extends TestCase
{

    /**
     * Wrap each test in a transaction so that
     * data is not persisted to the database.
     */
    use DatabaseTransactions;


    ///////////////////////////////////////////////////////////////////////////
    public function test_successful_comment_creation(): void {
        Event::fake();

        $essay   = Essay::factory()->create();
        $visitor = Visitor::factory()->create();
        $name    = 'Someone';
        $message = 'Some comment';
        $essay->addComment($visitor, $name, $message);

        $this->assertEquals(1, $essay->comments()->count());
        
        Event::assertDispatched(CommentCreated::class);
    }

}
