<?php

namespace Tests\Feature\API;

use App\Models\Book;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class CommentControllerTest extends TestCase
{

    /**
     * Wrap each test in a transaction so that
     * data is not persisted to the database.
     */
    use DatabaseTransactions;


    ///////////////////////////////////////////////////////////////////////////
    /**
     * Providing correct data with a commentable object
     * should result in the comment being created.
     */
    public function test_adding_comment_successfully(): void {
        $book       = $this->createActiveBook();
        $identifier = $book->getInteractionId();
        $data       = $this->getCorrectSampleData();

        $response = $this->testCommentURL($identifier, $data);

        $response->assertStatus(201);
    }

    ///////////////////////////////////////////////////////////////////////////
    private function createActiveBook(): Book {
        return Book::factory()->active()->create();
    }

    ///////////////////////////////////////////////////////////////////////////
    private function getCorrectSampleData(): array {
        return [
            'name'    => 'John Smith',
            'message' => 'Hello, world!',
        ];
    }

    ///////////////////////////////////////////////////////////////////////////
    private function testCommentURL(string $identifier, array $data = []): TestResponse {
        $params = ['id' => $identifier];
        $url    = route('api.comment', $params, false);

        return $this->post($url, $data);
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Not providing adequate input data should result in a bad request.
     */
    public function test_incorrect_input_data(): void {
        $book       = $this->createActiveBook();
        $identifier = $book->getInteractionId();
        $data       = []; // missing data

        $response = $this->testCommentURL($identifier, $data);

        $response->assertStatus(400);
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * When the identifier is invalid (i.e. it can't be decoded),
     * it's served as a not-found-response by the exception handler
     * as to not give away potentially sensitive information to
     * the end user.
     */
    public function test_invalid_identifier() {
        $identifier = 'test';
        $data       = $this->getCorrectSampleData();

        $response   = $this->testCommentURL($identifier, $data);
        $response->assertStatus(404);
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Trying to add a comment to a non-commentable object
     * should result in a not-found response, even if it does
     * in fact exist.
     */
    public function test_non_commentable_object(): void {
        $user       = User::factory()->create();
        $identifier = encrypt(get_class($user) . "{$user->id}");
        $data       = $this->getCorrectSampleData();

        $response = $this->testCommentURL($identifier, $data);

        $response->assertStatus(404);
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * When the identifier is correct, but the commentable object
     * is missing, a not-found response should be served.
     */
    public function test_no_main_object(): void {
        $book       = $this->createActiveBook();
        $identifier = $book->getInteractionId();
        $data       = $this->getCorrectSampleData();

        // delete the book
        $book->delete();

        $response = $this->testCommentURL($identifier, $data);

        $response->assertStatus(404);
    }
}
