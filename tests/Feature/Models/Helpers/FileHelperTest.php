<?php

namespace Tests\Feature\Models\Helpers;

use Tests\TestCase;
use App\Models\Book;
use Illuminate\Http\UploadedFile;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class FileHelperTest extends TestCase
{

    /**
     * Wrap each test in a transaction so that
     * data is not persisted to the database.
     */
    use DatabaseTransactions;


    ///////////////////////////////////////////////////////////////////////////
    public function test_save_file(): void {
        $book       = Book::factory()->create();
        $book->id   = 1;
        $attachment = $book->attachments()->createQuietly([
            'original_file_name' => 'test.txt',
            'server_file_name'   => 'test.txt',
            'mime_type'          => 'text/plain',
        ]);

        $helper = $attachment->getFileHelper();

        $this->assertFileDoesNotExist($helper->getFilePath());
        $this->assertFileDoesNotExist($helper->getThumbFilePath());
        
        $file = UploadedFile::fake()->create('file.txt');
        
        $helper->save($file);
        
        $this->assertFileExists($helper->getFilePath());
        $this->assertFileDoesNotExist($helper->getThumbFilePath());
    }

    ///////////////////////////////////////////////////////////////////////////
    public function test_save_thumbnail(): void {
        $book       = Book::factory()->create();
        $book->id   = 1;
        $attachment = $book->attachments()->createQuietly([
            'original_file_name' => 'test.jpg',
            'server_file_name'   => 'test.jpg',
            'mime_type'          => 'image/jpeg',
        ]);

        $helper = $attachment->getFileHelper();

        $this->assertFileDoesNotExist($helper->getFilePath());
        
        $file = UploadedFile::fake()->image('file.jpg', 1024, 768);
        $helper->save($file);
        
        $this->assertFileExists($helper->getFilePath());
        $this->assertFileExists($helper->getThumbFilePath());
    }

    ///////////////////////////////////////////////////////////////////////////
    public function test_delete_file(): void {
        $book       = Book::factory()->create();
        $book->id   = 1;
        $attachment = $book->attachments()->createQuietly([
            'original_file_name' => 'test.txt',
            'server_file_name'   => 'test.txt',
            'mime_type'          => 'text/plain',
        ]);

        $helper = $attachment->getFileHelper();
        $file   = UploadedFile::fake()->create('file.txt');
        
        $helper->save($file);

        $this->assertFileExists($helper->getFilePath());

        $helper->delete();
        
        $this->assertFileDoesNotExist($helper->getFilePath());
    }

    ///////////////////////////////////////////////////////////////////////////
    public function test_get_file_path(): void {
        $book       = Book::factory()->create();
        $book->id   = 1;
        $attachment = $book->attachments()->createQuietly([
            'original_file_name' => 'test.txt',
            'server_file_name'   => 'test.txt',
            'mime_type'          => 'text/plain',
        ]);

        $fileHelper = $attachment->getFileHelper();

        $this->assertStringEndsWith('/testing/Book/1/test.txt',       $fileHelper->getUrl());
        $this->assertStringEndsWith('/testing/Book/1/test-thumb.txt', $fileHelper->getThumbUrl());
    }

    ///////////////////////////////////////////////////////////////////////////
    public function test_get_url(): void {
        $book       = Book::factory()->create();
        $book->id   = 1;
        $attachment = $book->attachments()->createQuietly([
            'original_file_name' => 'test.txt',
            'server_file_name'   => 'test.txt',
            'mime_type'          => 'text/plain',
        ]);

        $fileHelper = $attachment->getFileHelper();

        $this->assertSame('http://ypetrov.localhost/testing/Book/1/test.txt',       $fileHelper->getUrl());
        $this->assertSame('http://ypetrov.localhost/testing/Book/1/test-thumb.txt', $fileHelper->getThumbUrl());
    }

    ///////////////////////////////////////////////////////////////////////////
    public function test_width_and_height_of_image(): void {
        $book       = Book::factory()->create();
        $book->id   = 1;
        $attachment = $book->attachments()->createQuietly([
            'original_file_name' => 'test.jpg',
            'server_file_name'   => 'test.jpg',
            'mime_type'          => 'image/jpeg',
        ]);

        $helper = $attachment->getFileHelper();

        $this->assertFileDoesNotExist($helper->getFilePath());
        
        $file = UploadedFile::fake()->image('file.jpg', 1024, 768);
        $helper->save($file);

        $this->assertSame(1024, $helper->getWidth());
        $this->assertSame(768,  $helper->getHeight());
    }

    ///////////////////////////////////////////////////////////////////////////
    public function test_width_and_height_of_non_image(): void {
        $book       = Book::factory()->create();
        $book->id   = 1;
        $attachment = $book->attachments()->createQuietly([
            'original_file_name' => 'test.txt',
            'server_file_name'   => 'test.txt',
            'mime_type'          => 'text/plain',
        ]);

        $helper = $attachment->getFileHelper();

        $this->assertFileDoesNotExist($helper->getFilePath());
        
        $file = UploadedFile::fake()->create('file.txt');
        $helper->save($file);

        $this->assertNull($helper->getWidth());
        $this->assertNull($helper->getHeight());
    }

}
