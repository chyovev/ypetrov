<?php

namespace Tests\Feature;

use App\Models\Book;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class WorkControllerTest extends TestCase
{

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Active books can be accessed by their slugs.
     */
    public function test_fetching_of_active_book(): void {
        $book = Book::factory()->active()->create();

        $response = $this->testBookPublicURL($book->slug);

        $response->assertStatus(200);
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Inactive books result in a 404 (Not Found) error.
     */
    public function test_fetching_of_inactive_book(): void {
        $book = Book::factory()->inactive()->create();

        $response = $this->testBookPublicURL($book->slug);

        $response->assertStatus(404);
    }

    ///////////////////////////////////////////////////////////////////////////
    private function testBookPublicURL(string $slug): TestResponse {
        $url = route('book', ['bookSlug' => $slug], false);

        return $this->get($url);
    }
}
