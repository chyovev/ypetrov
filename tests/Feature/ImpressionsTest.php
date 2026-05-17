<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Book;
use App\Models\Visitor;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Symfony\Component\HttpFoundation\Response;

class ImpressionsTest extends TestCase
{

    /**
     * Wrap each test in a transaction so that
     * data is not persisted to the database.
     */
    use DatabaseTransactions;


    ///////////////////////////////////////////////////////////////////////////
    /**
     * An impression is registered once a statsable object
     * is loaded through the controller.
     */
    public function test_successful_impression_registration(): void {
        $this->app->instance(Visitor::class, Visitor::factory()->create());

        $book     = Book::factory()->active()->create();
        $endpoint = route('book', ['book' => $book]);
        
        $this->assertEquals(0, $book->getTotalImpressions());

        $response = $this->get($endpoint);
        $response->assertStatus(Response::HTTP_OK);

        $this->assertEquals(1, $book->getTotalImpressions());
    }

}
