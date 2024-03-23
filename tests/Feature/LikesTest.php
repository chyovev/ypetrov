<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Book;
use Illuminate\Testing\TestResponse;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Symfony\Component\HttpFoundation\Response;

class LikesTest extends TestCase
{

    /**
     * Wrap each test in a transaction so that
     * data is not persisted to the database.
     */
    use DatabaseTransactions;


    ///////////////////////////////////////////////////////////////////////////
    /**
     * An active statsable object can usually be liked by a visitor.
     */
    public function test_successful_like_registration(): void {
        $book = Book::factory()->active()->create();
        
        $this->assertEquals(0, $book->getTotalLikes());

        $response = $this->testLikeResponse($book);
        $response->assertStatus(Response::HTTP_CREATED);

        $this->assertEquals(1, $book->getTotalLikes());
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Send a request to like a book.
     * 
     * @param  Book $book
     * @return TestResponse
     */
    private function testLikeResponse(Book $book): TestResponse {
        $identifier = $book->getInteractionId();

        $endpoint = route('api.like', ['id' => $identifier]);

        return $this->post($endpoint);
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * If a visitor has already liked a statsable object, liking it
     * again would result in a conflict response.
     */
    public function test_unsuccessful_double_like_registration(): void {
        $book = Book::factory()->active()->create();

        $response = $this->testLikeResponse($book);
        $response->assertStatus(Response::HTTP_CREATED);

        $response = $this->testLikeResponse($book);
        $response->assertStatus(Response::HTTP_CONFLICT);
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * A visitor can revoke their like on a statsable object
     * that they have previously liked.
     */
    public function test_successful_like_revoke(): void {
        $book = Book::factory()->active()->create();

        $response = $this->testLikeResponse($book);
        $response->assertStatus(Response::HTTP_CREATED);

        $response = $this->testRevokeLikeResponse($book);
        $response->assertStatus(Response::HTTP_NO_CONTENT);
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Send a request to revoke like from a book.
     * 
     * @param  Book $book
     * @return TestResponse
     */
    private function testRevokeLikeResponse(Book $book): TestResponse {
        $identifier = $book->getInteractionId();

        $endpoint = route('api.revoke_like', ['id' => $identifier]);

        return $this->delete($endpoint);
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * If a visitor tries to revoke their like on a statsable object
     * that they have *not* previously liked, this would result in a
     * conflict response.
     */
    public function test_unsuccessful_like_revoke(): void {
        $book = Book::factory()->active()->create();

        $response = $this->testRevokeLikeResponse($book);
        $response->assertStatus(Response::HTTP_CONFLICT);
    }
}
