<?php

namespace Tests\Feature\Listeners;

use App\Events\AttachmentDeleted;
use App\Listeners\DeleteAttachmentFile;
use File;
use App\Models\Attachment;
use Tests\TestCase;
use App\Models\Book;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Event;

class DeleteAttachmentFileTest extends TestCase
{

    /**
     * Wrap each test in a transaction so that
     * data is not persisted to the database.
     */
    use DatabaseTransactions;


    ///////////////////////////////////////////////////////////////////////////
    public function test_attachment_file_deleted(): void {
        $book       = Book::factory()->create();
        $attachment = $this->addAttachment($book, 'test.txt');

        $this->assertFileExists($attachment->getFileHelper()->getFilePath());

        $event = new AttachmentDeleted($attachment);
        $this->app->make(DeleteAttachmentFile::class)->handle($event);

        $this->assertFileDoesNotExist($attachment->getFileHelper()->getFilePath());
    }

    ///////////////////////////////////////////////////////////////////////////
    public function test_attachment_file_deleted_subfolder_deleted(): void {
        $book       = Book::factory()->create();
        $attachment = $this->addAttachment($book, 'test.txt');

        $event = new AttachmentDeleted($attachment);
        $this->app->make(DeleteAttachmentFile::class)->handle($event);

        $filePath          = $attachment->getFileHelper()->getFilePath();
        $parentIdFolder    = File::dirname($filePath);
        $parentClassFolder = File::dirname($parentIdFolder);

        $this->assertFileDoesNotExist($filePath);
        $this->assertFileDoesNotExist($parentIdFolder);
        $this->assertFileDoesNotExist($parentClassFolder);
    }

    ///////////////////////////////////////////////////////////////////////////
    public function test_attachment_file_deleted_subfolder_not_deleted(): void {
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

        $event = new AttachmentDeleted($attachment1);
        $this->app->make(DeleteAttachmentFile::class)->handle($event);

        $this->assertFileDoesNotExist($filePath1);
        $this->assertFileExists($filePath2);
        $this->assertFileExists($parentIdFolder1);
        $this->assertFileExists($parentClassFolder1);
    }

    ///////////////////////////////////////////////////////////////////////////
    public function test_attachment_file_deleted_group_folder_not_deleted(): void {
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

        $event = new AttachmentDeleted($attachment1);
        $this->app->make(DeleteAttachmentFile::class)->handle($event);

        $this->assertFileDoesNotExist($filePath1);
        $this->assertFileExists($filePath2);
        $this->assertFileDoesNotExist($parentIdFolder1);
        $this->assertFileExists($parentIdFolder2);
        $this->assertFileExists($parentClassFolder1);

    }

    ///////////////////////////////////////////////////////////////////////////
    public function test_attachment_deleted_quietly_no_event_dispatched(): void {
        Event::fake();

        $book       = Book::factory()->create();
        $attachment = $this->addAttachment($book, 'test.txt');

        $this->assertFileExists($attachment->getFileHelper()->getFilePath());

        $attachment->deleteQuietly();

        $this->assertFileExists($attachment->getFileHelper()->getFilePath());

        Event::assertNotDispatched(AttachmentDeleted::class);
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
