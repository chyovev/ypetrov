<?php

namespace Tests\Feature\Models\Helpers;

use Tests\TestCase;
use App\Models\Book;
use App\Models\Attachment;
use App\Models\Helpers\UploadHelper;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;

class UploadHelperTest extends TestCase
{

    /**
     * Wrap each test in a transaction so that
     * data is not persisted to the database.
     */
    use DatabaseTransactions;


    ///////////////////////////////////////////////////////////////////////////
    /**
     * Uploading a file for a single attachable object should
     * create an Attachment record and move the file to a
     * respective subfolder.
     */
    public function test_successful_file_upload(): void {
        /** @var Book */
        $book     = Book::factory()->create();
        $helper   = new UploadHelper($book);
        $tempFile = UploadedFile::fake()->create('Hello world.txt', 'Test');
        $md5      = md5_file($tempFile->path());

        $this->assertEquals(0, $book->attachments()->count());
        $this->assertFileExists($tempFile->path());

        $attachment = $helper->upload($tempFile);

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
        /** @var Book */
        $book     = Book::factory()->create();
        $tempFile = UploadedFile::fake()->create('Hello кирилица wor%ld.txt');
        $helper   = new UploadHelper($book);

        $attachment = $helper->upload($tempFile);

        $this->assertEquals('hello-kirilica-world.txt', $attachment->server_file_name);
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Uploading multiple files with the same file name
     * will append a counter suffix to all duplicates
     * while keeping the original file name in the database.
     */
    public function test_uploading_of_multiple_files_with_same_name(): void {
        /** @var Book */
        $book   = Book::factory()->create();
        $helper = new UploadHelper($book);
        
        $tempFile1   = UploadedFile::fake()->create('file.txt');
        $attachment1 = $helper->upload($tempFile1);
        $this->assertFileExists($attachment1->getServerFilePath());
        $this->assertEquals('file.txt',   $attachment1->original_file_name);
        $this->assertEquals('file.txt',   $attachment1->server_file_name);

        $tempFile2   = UploadedFile::fake()->create('file.txt');
        $attachment2 = $helper->upload($tempFile2);
        $this->assertFileExists($attachment2->getServerFilePath());
        $this->assertEquals('file.txt',   $attachment1->original_file_name);
        $this->assertEquals('file_1.txt', $attachment2->server_file_name);
        
        $tempFile3   = UploadedFile::fake()->create('file.txt');
        $attachment3 = $helper->upload($tempFile3);
        $this->assertFileExists($attachment3->getServerFilePath());
        $this->assertEquals('file.txt',   $attachment1->original_file_name);
        $this->assertEquals('file_2.txt', $attachment3->server_file_name);

        $this->assertEquals(3, $book->attachments->count());
    }
}
