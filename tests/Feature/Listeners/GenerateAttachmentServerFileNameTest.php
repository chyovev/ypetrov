<?php

namespace Tests\Feature\Listeners;

use App\Models\Attachment;
use App\Models\Book;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class GenerateAttachmentServerFileNameTest extends TestCase
{

    use DatabaseTransactions;

    ///////////////////////////////////////////////////////////////////////////
    public function test_server_file_name_already_present(): void {
        $book       = Book::factory()->create();
        $attachment = Attachment::factory()->make([
            'original_file_name' => 'original.pdf',
            'server_file_name'   => 'server.pdf',
        ]);

        $book->attachments()->save($attachment);

        $this->assertSame('server.pdf', $attachment->server_file_name);
    }

    ///////////////////////////////////////////////////////////////////////////
    public function test_server_file_name_generated(): void {
        $book       = Book::factory()->create();
        $attachment = Attachment::factory()->make([
            'original_file_name' => 'original.pdf',
            'server_file_name'   => null,
        ]);

        $book->attachments()->save($attachment);

        $this->assertSame('original.pdf', $attachment->server_file_name);
    }

    ///////////////////////////////////////////////////////////////////////////
    public function test_server_file_name_generated_with_suffix(): void {
        $book        = Book::factory()->create();
        $attachment1 = Attachment::factory()->make([
            'original_file_name' => 'original.pdf',
            'server_file_name'   => null,
        ]);

        $book->attachments()->save($attachment1);

        $attachment1->getFileHelper()->save('test');

        $attachment2 = Attachment::factory()->make([
            'original_file_name' => 'original.pdf',
            'server_file_name'   => null,
        ]);

        $book->attachments()->save($attachment2);

        $this->assertSame('original_1.pdf', $attachment2->server_file_name);
    }

}