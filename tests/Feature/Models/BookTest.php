<?php

namespace Tests\Feature\Models;

use App\Models\Book;
use Tests\TestCase;

class BookTest extends TestCase
{

    ///////////////////////////////////////////////////////////////////////////
    public function test_book_meta_title_no_year(): void {
        $book = Book::factory()->make([
            'title'        => 'Test',
            'publish_year' => null,
        ]);

        $this->assertSame('Test', $book->getMetaTitle());
    }

    ///////////////////////////////////////////////////////////////////////////
    public function test_book_meta_title_with_year(): void {
        $book = Book::factory()->make([
            'title'        => 'Test',
            'publish_year' => 2026,
        ]);

        $this->assertSame('Test (2026)', $book->getMetaTitle());
    }

    ///////////////////////////////////////////////////////////////////////////
    public function test_book_meta_description_with_text(): void {
        $book = Book::factory()->make([
            'text' => 'Lorem ipsum dolor',
        ]);

        $this->assertSame('Lorem ipsum dolor', $book->getMetaDescription());
    }

    ///////////////////////////////////////////////////////////////////////////
    public function test_book_meta_description_without_text(): void {
        $book = Book::factory()->make([
            'title'        => 'Test',
            'publish_year' => 2026,
            'publisher'    => 'Publish Inc.',
            'text'         => null,
        ]);

        $this->assertSame('Test (2026), издателство Publish Inc.', $book->getMetaDescription());
    }

}
