<?php

namespace Tests\Feature;

use File;
use Tests\TestCase;
use App\Models\Book;
use App\Models\Attachment;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;

class AttachableTest extends TestCase
{

    /**
     * Wrap each test in a transaction so that
     * data is not persisted to the database.
     */
    use DatabaseTransactions;

    ///////////////////////////////////////////////////////////////////////////
    public function tearDown(): void {
        $this->deleteTestingFolder();

        // call the parent tearDown to close any hanging transactions
        parent::tearDown();
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Files uploaded during testing are stored in the testing folder
     * which should be purged once the tests are completed.
     * 
     * @return void
     */
    private function deleteTestingFolder(): void {
        $folder = $this->getTestingPath();

        File::deleteDirectory($folder);
    }

    ///////////////////////////////////////////////////////////////////////////
    private function getTestingPath(): string {
        return public_path( app()->environment() );
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Uploading a file for a single attachable object should
     * create an Attachment record and move the file to a
     * respective subfolder.
     */
    public function test_successful_file_upload(): void {
        $book     = Book::factory()->create();
        $tempFile = UploadedFile::fake()->create('Hello world.txt', 'Test');
        $md5      = md5_file($tempFile->path());

        $this->assertEquals(0, $book->attachments()->count());
        $this->assertFileExists($tempFile->path());

        $attachment = $book->uploadAttachment($tempFile);

        $this->assertEquals(1, $book->attachments()->count());
        $this->assertFileDoesNotExist($tempFile->path());

        $this->assertInstanceOf(Attachment::class, $attachment);
        $this->assertTrue($attachment->wasRecentlyCreated);
        $this->assertSame($attachment->id, $book->attachments()->first()->id);
        $this->assertSame('Hello world.txt', $attachment->original_file_name);
        $this->assertSame('hello-world.txt', $attachment->server_file_name);
        $this->assertFileExists($attachment->getServerFilePath());
        $this->assertIsReadable($attachment->getServerFilePath());
        $this->assertSame($md5, md5_file($attachment->getServerFilePath()));
        $this->assertStringContainsString("/testing/Book/{$book->id}", $attachment->getServerFilePath());
        $this->assertSame("http://ypetrov.localhost/testing/Book/{$book->id}/hello-world.txt", $attachment->getURL());
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * The original file name is sanitized during upload, so white
     * spaces and funny non-ascii characters are cleaned up.
     */
    public function test_file_name_sanitization(): void {
        $book     = Book::factory()->create();
        $tempFile = UploadedFile::fake()->create('Hello кирилица wor%ld.txt');

        $attachment = $book->uploadAttachment($tempFile);

        $this->assertEquals('hello-kirilica-world.txt', $attachment->server_file_name);
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Uploading multiple files with the same file name
     * will append a counter suffix to all duplicates
     * while keeping the original file name in the database.
     */
    public function test_uploading_of_multiple_files_with_same_name(): void {
        $book = Book::factory()->create();
        
        $tempFile1   = UploadedFile::fake()->create('file.txt');
        $attachment1 = $book->uploadAttachment($tempFile1);
        $this->assertFileExists($attachment1->getServerFilePath());
        $this->assertEquals('file.txt',   $attachment1->original_file_name);
        $this->assertEquals('file.txt',   $attachment1->server_file_name);

        $tempFile2   = UploadedFile::fake()->create('file.txt');
        $attachment2 = $book->uploadAttachment($tempFile2);
        $this->assertFileExists($attachment2->getServerFilePath());
        $this->assertEquals('file.txt',   $attachment1->original_file_name);
        $this->assertEquals('file_1.txt', $attachment2->server_file_name);
        
        $tempFile3   = UploadedFile::fake()->create('file.txt');
        $attachment3 = $book->uploadAttachment($tempFile3);
        $this->assertFileExists($attachment3->getServerFilePath());
        $this->assertEquals('file.txt',   $attachment1->original_file_name);
        $this->assertEquals('file_2.txt', $attachment3->server_file_name);

        $this->assertEquals(3, $book->attachments->count());
    }

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
    public function test_attachment_delete_observer(): void {
        $book     = Book::factory()->create();
        $tempFile = UploadedFile::fake()->create('file.txt');

        $attachment = $book->uploadAttachment($tempFile);

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
     * Once an attachable record gets deleted, all its attachment
     * records get cycled through and deleted individually.
     * From then on, the Attachment observer catches this event
     * and deletes the actual file.
     * 
     * NB! Observers for attachable models are registered
     *     upon object initialization and not on app boot.
     */
    public function test_attachable_delete_observer(): void {
        $book       = Book::factory()->create();
        $tempFile   = UploadedFile::fake()->create('file.txt');
        $attachment = $book->uploadAttachment($tempFile);
        $filePath   = $attachment->getServerFilePath();

        $this->assertSame(1, $book->attachments()->count());
        $this->assertFileExists($filePath);

        $book->delete();

        $this->assertSame(0, $book->attachments()->count());
        $this->assertFileDoesNotExist($filePath);
    }
    
}
