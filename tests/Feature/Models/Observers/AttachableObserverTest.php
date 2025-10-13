<?php

namespace Tests\Feature\Models\Observers;

use Tests\TestCase;
use App\Models\Book;
use App\Models\Attachment;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;

class AttachableObserverTest extends TestCase
{

    /**
     * Wrap each test in a transaction so that
     * data is not persisted to the database.
     */
    use DatabaseTransactions;


    ///////////////////////////////////////////////////////////////////////////
    /**
     * Once an attachable record gets deleted, all its attachment
     * records get cycled through and deleted individually.
     * From then on, the Attachment observer catches this event
     * and deletes the actual file.
     * 
     * NB! Observers for attachable models are registered
     *     upon object initialization and not on app boot.
     */
    public function test_attachable_observer_delete(): void {
        $book       = Book::factory()->create();
        $attachment = $this->addAttachment($book);
        $filePath   = $attachment->getServerFilePath();

        $this->assertSame(1, $book->attachments()->count());
        $this->assertFileExists($filePath);

        $book->delete();

        $this->assertSame(0, $book->attachments()->count());
        $this->assertFileDoesNotExist($filePath);
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * If the attachable record is deleted quietly, this would bypass
     * the observer and both the attachment record and physical file
     * will not be deleted.
     * (They still get deleted afterwards by the transaction rollback
     * and the tearDown method)
     */
    public function test_attachable_observer_delete_quietly(): void {
        $book       = Book::factory()->create();
        $attachment = $this->addAttachment($book);
        $filePath   = $attachment->getServerFilePath();

        $this->assertSame(1, $book->attachments()->count());
        $this->assertFileExists($filePath);

        $book->deleteQuietly();

        $this->assertSame(1, $book->attachments()->count());
        $this->assertFileExists($filePath);
    }

    ///////////////////////////////////////////////////////////////////////////
    private function addAttachment(Book $book): Attachment {
        $attachment = $book->attachments()->createQuietly([
            'original_file_name' => 'test.txt',
            'server_file_name'   => 'test.txt',
            'mime_type'          => 'application/txt',
        ]);

        $file = UploadedFile::fake()->create('test');
        $file->move($attachment->getFileHelper()->getFilePath(), $attachment->server_file_name);

        return $attachment;
    }
    
}
