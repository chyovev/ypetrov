<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Essay;
use App\Models\Visitor;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CommentableTest extends TestCase
{

    /**
     * Wrap each test in a transaction so that
     * data is not persisted to the database.
     */
    use DatabaseTransactions;


    ///////////////////////////////////////////////////////////////////////////
    public function test_successful_comment_creation(): void {
        $essay   = Essay::factory()->create();
        $visitor = Visitor::factory()->create();
        $name    = 'Someone';
        $message = 'Some comment';
        $essay->addComment($visitor, $name, $message);

        $this->assertEquals(1, $essay->comments()->count());
    }

}
