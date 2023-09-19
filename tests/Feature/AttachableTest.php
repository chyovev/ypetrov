<?php

namespace Tests\Feature;

use File;
use Tests\TestCase;
use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AttachableTest extends TestCase
{

    /**
     * Refresh the database before executing all
     * the tests to make sure no records are present.
     */
    use RefreshDatabase;

    ///////////////////////////////////////////////////////////////////////////
    public function tearDown(): void {
        $this->deleteTestingFolder();
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
        $tempFile = $this->createTempFile('Hello world.txt');

        $attachment = $book->uploadAttachment($tempFile);

        $this->assertEquals(1, $book->attachments()->count());
        $this->assertFileExists($attachment->getServerFilePath());
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * The original file name is sanitized during upload, so white
     * spaces and funny non-ascii characters are cleaned up.
     */
    public function test_file_name_sanitization(): void {
        $book     = Book::factory()->create();
        $tempFile = $this->createTempFile('Hello кирилица wor%ld.txt');

        $attachment = $book->uploadAttachment($tempFile);

        $this->assertEquals('hello-kirilica-world.txt', $attachment->server_file_name);
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Uploading multiple files with the same file name
     * will append a counter suffix to all duplicates
     * while keeping the original file name in the database.
     */
    public function test_upading_of_multiple_files_with_same_name(): void {
        $book     = Book::factory()->create();
        
        $tempFile1   = $this->createTempFile('file.txt');
        $attachment1 = $book->uploadAttachment($tempFile1);
        $this->assertEquals('file.txt',   $attachment1->original_file_name);
        $this->assertEquals('file.txt',   $attachment1->server_file_name);

        $tempFile2   = $this->createTempFile('file.txt');
        $attachment2 = $book->uploadAttachment($tempFile2);
        $this->assertEquals('file.txt',   $attachment1->original_file_name);
        $this->assertEquals('file_1.txt', $attachment2->server_file_name);
        
        $tempFile3   = $this->createTempFile('file.txt');
        $attachment3 = $book->uploadAttachment($tempFile3);
        $this->assertEquals('file.txt',   $attachment1->original_file_name);
        $this->assertEquals('file_2.txt', $attachment3->server_file_name);

        $this->assertEquals(3, $book->attachments->count());
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Once an Attachment record gets deleted, its file
     * gets deleted from the server, too.
     */
    public function test_attachment_delete_observer(): void {
        $book     = Book::factory()->create();
        $tempFile = $this->createTempFile('file.txt');

        $attachment = $book->uploadAttachment($tempFile);
        $filePath   = $attachment->getServerFilePath();

        $this->assertFileExists($filePath);

        $attachment->delete();
        $this->assertFileDoesNotExist($filePath);
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
        $tempFile   = $this->createTempFile('file.txt');
        $attachment = $book->uploadAttachment($tempFile);
        $filePath   = $attachment->getServerFilePath();

        $this->assertFileExists($filePath);

        $book->delete();

        $this->assertFileDoesNotExist($filePath);
    }
    
    ///////////////////////////////////////////////////////////////////////////
    /**
     * Create a temp file using faker and return its path.
     * 
     * NB! Faker uses uuid for the file name, but this can
     *     be worked around using the $fileName parameter.
     * 
     * @param  string $fileName
     * @return string
     */
    private function createTempFile(string $fileName): string {
        $sourceFolder = $this->getAssetsPath();
        $targetFolder = $this->getTestingPath();
        File::ensureDirectoryExists($targetFolder);

        $sourcePath   = fake()->file($sourceFolder, $targetFolder);
        $newFilePath  = File::dirname($sourcePath) . DIRECTORY_SEPARATOR . $fileName;

        File::move($sourcePath, $newFilePath);

        return $newFilePath;
    }
}
