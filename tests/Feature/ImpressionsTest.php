<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Book;
use Illuminate\Database\QueryException;
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
        $book     = Book::factory()->active()->create();
        $endpoint = route('book', ['bookSlug' => $book->slug]);
        
        $this->assertEquals(0, $book->getTotalImpressions());

        $response = $this->get($endpoint);
        $response->assertStatus(Response::HTTP_OK);

        $this->assertEquals(1, $book->getTotalImpressions());
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Impressions registration relies on a Visitor record which is
     * registered as an instance by the RegisterVisitor middleware.
     * Calling the addImpression() method on a statsable object
     * would result in a databse exception since there will be no
     * Visitor record instance.
     */
    public function test_unsuccessful_impression_registration(): void {
        $book = Book::factory()->active()->create();

        $this->assertEquals(0, $book->getTotalImpressions());

        $this->expectException(QueryException::class);
        
        $book->addImpression();
    }
}
