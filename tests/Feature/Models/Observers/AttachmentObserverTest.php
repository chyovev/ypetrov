<?php

namespace Tests\Feature\Models\Observers;

use App\Models\Attachment;
use Tests\TestCase;
use App\Models\Book;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;

class AttachmentObserverTest extends TestCase
{

    /**
     * Wrap each test in a transaction so that
     * data is not persisted to the database.
     */
    use DatabaseTransactions;


    ///////////////////////////////////////////////////////////////////////////
    /**
     * Once an Attachment record gets deleted, its file
     * gets deleted from the server, too.
     * 
     * NB! If an attachment is already loaded in memory either by itself
     *     or as a part of a collection, deleting it won't automatically
     *     purge it from the memory (which is not a bug, just something
     *     to keep in mind)
     */
    public function test_attachment_observer_delete(): void {
        $book       = Book::factory()->create();
        $attachment = $this->addAttachment($book);

        $this->assertSame(1, $book->attachments()->count());
        $this->assertFileExists($attachment->getServerFilePath());

        $this->assertCount(1, $book->attachments);
        $this->assertNotNull($attachment);
        $this->assertTrue($attachment->exists);

        $attachment->delete();

        $this->assertSame(0, $book->attachments()->count());
        $this->assertFileDoesNotExist($attachment->getServerFilePath());

        $this->assertCount(1, $book->attachments);
        $this->assertNotNull($attachment);
        $this->assertFalse($attachment->exists);
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * If the attachment is deleted quietly, this would bypass
     * the observer and the file would not be deleted.
     * (It still gets deleted afterwards by the tearDown method)
     */
    public function test_attachment_observer_delete_quietly(): void {
        $book       = Book::factory()->create();
        $attachment = $this->addAttachment($book);

        $this->assertSame(1, $book->attachments()->count());
        $this->assertFileExists($attachment->getServerFilePath());

        $attachment->deleteQuietly();

        $this->assertSame(0, $book->attachments()->count());
        $this->assertFileExists($attachment->getServerFilePath());
    }

    ///////////////////////////////////////////////////////////////////////////
    private function addAttachment(Book $book): Attachment {
        $attachment = $book->attachments()->createQuietly([
            'original_file_name' => 'test.txt',
            'server_file_name'   => 'test.txt',
            'mime_type'          => 'application/txt',
        ]);

        $file = UploadedFile::fake()->create('test');
        $file->move($attachment->getAbsolutePath(), $attachment->server_file_name);

        return $attachment;
    }
    
}
