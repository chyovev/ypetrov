<?php

namespace Tests\Feature\Models\Observers;

use File;
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
        $attachment = $this->addAttachment($book, 'test.txt');

        $this->assertSame(1, $book->attachments()->count());
        $this->assertFileExists($attachment->getFileHelper()->getFilePath());

        $this->assertCount(1, $book->attachments);
        $this->assertNotNull($attachment);
        $this->assertTrue($attachment->exists);

        $attachment->delete();

        $this->assertSame(0, $book->attachments()->count());
        $this->assertFileDoesNotExist($attachment->getFileHelper()->getFilePath());

        $this->assertCount(1, $book->attachments);
        $this->assertNotNull($attachment);
        $this->assertFalse($attachment->exists);
    }

    ///////////////////////////////////////////////////////////////////////////
    public function test_deleting_subfolders_when_last_file_of_same_object_is_deleted(): void {
        $book       = Book::factory()->create();
        $attachment = $this->addAttachment($book, 'test.txt');

        $attachment->delete();

        $filePath          = $attachment->getFileHelper()->getFilePath();
        $parentIdFolder    = File::dirname($filePath);
        $parentClassFolder = File::dirname($parentIdFolder);

        $this->assertFileDoesNotExist($filePath);
        $this->assertFileDoesNotExist($parentIdFolder);
        $this->assertFileDoesNotExist($parentClassFolder);
    }

    ///////////////////////////////////////////////////////////////////////////
    public function test_not_deleting_subfolders_when_other_files_of_same_object_remaining(): void {
        $book        = Book::factory()->create();
        $attachment1 = $this->addAttachment($book, 'test1.txt');
        $attachment2 = $this->addAttachment($book, 'test2.txt');

        $filePath1          = $attachment1->getFileHelper()->getFilePath();
        $parentIdFolder1    = File::dirname($filePath1);
        $parentClassFolder1 = File::dirname($parentIdFolder1);

        $filePath2          = $attachment2->getFileHelper()->getFilePath();
        $parentIdFolder2    = File::dirname($filePath2);
        $parentClassFolder2 = File::dirname($parentIdFolder2);

        $this->assertNotSame($filePath1,       $filePath2);
        $this->assertSame($parentIdFolder1,    $parentIdFolder2);
        $this->assertSame($parentClassFolder1, $parentClassFolder2);

        $attachment1->delete();

        $this->assertFileDoesNotExist($filePath1);
        $this->assertFileExists($filePath2);
        $this->assertFileExists($parentIdFolder1);
        $this->assertFileExists($parentClassFolder1);
    }

    ///////////////////////////////////////////////////////////////////////////
    public function test_not_deleting_class_subfolder_when_files_of_other_objects_remaining(): void {
        $book1       = Book::factory()->create();
        $attachment1 = $this->addAttachment($book1, 'test.txt');

        $book2       = Book::factory()->create();
        $attachment2 = $this->addAttachment($book2, 'test.txt');

        $filePath1          = $attachment1->getFileHelper()->getFilePath();
        $parentIdFolder1    = File::dirname($filePath1);
        $parentClassFolder1 = File::dirname($parentIdFolder1);

        $filePath2          = $attachment2->getFileHelper()->getFilePath();
        $parentIdFolder2    = File::dirname($filePath2);
        $parentClassFolder2 = File::dirname($parentIdFolder2);

        $this->assertNotSame($filePath1,       $filePath2);
        $this->assertNotSame($parentIdFolder1, $parentIdFolder2);
        $this->assertSame($parentClassFolder1, $parentClassFolder2);

        $attachment1->delete();

        $this->assertFileDoesNotExist($filePath1);
        $this->assertFileExists($filePath2);
        $this->assertFileDoesNotExist($parentIdFolder1);
        $this->assertFileExists($parentIdFolder2);
        $this->assertFileExists($parentClassFolder1);

    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * If the attachment is deleted quietly, this would bypass
     * the observer and the file would not be deleted.
     * (It still gets deleted afterwards by the tearDown method)
     */
    public function test_attachment_observer_delete_quietly(): void {
        $book       = Book::factory()->create();
        $attachment = $this->addAttachment($book, 'test.txt');

        $this->assertSame(1, $book->attachments()->count());
        $this->assertFileExists($attachment->getFileHelper()->getFilePath());

        $attachment->deleteQuietly();

        $this->assertSame(0, $book->attachments()->count());
        $this->assertFileExists($attachment->getFileHelper()->getFilePath());
    }

    ///////////////////////////////////////////////////////////////////////////
    private function addAttachment(Book $book, string $serverFileName): Attachment {
        $attachment = $book->attachments()->createQuietly([
            'original_file_name' => 'test.txt',
            'server_file_name'   => $serverFileName,
            'mime_type'          => 'application/txt',
        ]);

        $file       = UploadedFile::fake()->create('test');
        $targetPath = File::dirname($attachment->getFileHelper()->getFilePath());
        
        $file->move($targetPath, $attachment->server_file_name);

        return $attachment;
    }
    
}
