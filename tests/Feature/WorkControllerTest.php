<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Poem;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class WorkControllerTest extends TestCase
{

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Active books can be accessed by their slugs.
     */
    public function test_fetching_of_active_book(): void {
        $book = $this->createActiveBook();

        $response = $this->testBookPublicURL($book->slug);

        $response->assertStatus(200);
    }

    ///////////////////////////////////////////////////////////////////////////
    private function createActiveBook(): Book {
        return Book::factory()->active()->create();
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Inactive books result in a 404 (Not Found) error.
     */
    public function test_fetching_of_inactive_book(): void {
        $book = $this->createInactiveBook();

        $response = $this->testBookPublicURL($book->slug);

        $response->assertStatus(404);
    }

    ///////////////////////////////////////////////////////////////////////////
    private function createInactiveBook(): Book {
        return Book::factory()->inactive()->create();
    }

    ///////////////////////////////////////////////////////////////////////////
    private function testBookPublicURL(string $slug): TestResponse {
        $url = route('book', ['bookSlug' => $slug], false);

        return $this->get($url);
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Poems can only be fetched via one of the books
     * they're associated with. Both of them need to be
     * marked as active.
     */
    public function test_fetching_of_active_poem(): void {
        $book = $this->createActiveBook();
        $poem = $this->createActivePoem($book);

        $response = $this->testPoemPublicURL($book->slug, $poem->slug);

        $response->assertStatus(200);
    }

    ///////////////////////////////////////////////////////////////////////////
    private function createActivePoem(Book $book): Poem {
        return Poem::factory()->active()->hasAttached($book)->create();
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Fetching an inactive poem for an active book should not work.
     */
    public function test_fetching_of_inactive_poem(): void {
        $book = $this->createActiveBook();
        $poem = $this->createInactivePoem($book);

        $response = $this->testPoemPublicURL($book->slug, $poem->slug);

        $response->assertStatus(404);
    }

    ///////////////////////////////////////////////////////////////////////////
    private function createInactivePoem(Book $book): Poem {
        return Poem::factory()->inactive()->hasAttached($book)->create();
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Fetching an active poem for an inactive book should also not work.
     */
    public function test_fetching_of_active_poem_for_inactive_book(): void {
        $book = $this->createInactiveBook();
        $poem = $this->createActivePoem($book);

        $response = $this->testPoemPublicURL($book->slug, $poem->slug);

        $response->assertStatus(404);
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * If an active poem is not associated with the book
     * it's trying to be fetched through, an error should occur.
     */
    public function test_fetching_of_active_poem_from_another_book(): void {
        $book1 = $this->createActiveBook();
        $book2 = $this->createActiveBook();
        $poem  = $this->createActivePoem($book1);

        $response = $this->testPoemPublicURL($book2->slug, $poem->slug);

        $response->assertStatus(404);
    }

    ///////////////////////////////////////////////////////////////////////////
    private function testPoemPublicURL(string $bookSlug, string $poemSlug): TestResponse {
        $params = [
            'bookSlug' => $bookSlug,
            'poemSlug' => $poemSlug,
        ];
        $url = route('poem', $params, false);

        return $this->get($url);
    }
}
