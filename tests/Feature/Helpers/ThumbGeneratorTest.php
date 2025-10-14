<?php

namespace Tests\Feature\Helpers;

use File;
use Tests\TestCase;
use App\Helpers\ThumbGenerator;
use Illuminate\Http\UploadedFile;

class ThumbGeneratorTest extends TestCase
{

    public function test_thumb_generation(): void {
        $file = UploadedFile::fake()->image('file.jpg', 1024, 768);

        $sourcePath = $file->getPathname();
        $targetPath = stream_get_meta_data(tmpfile())['uri'] . '.jpg';

        $this->assertFileExists($sourcePath);
        $this->assertFileDoesNotExist($targetPath);

        list($width, $height) = getimagesize($sourcePath);

        $this->assertSame('image/jpeg', $file->getMimeType());
        $this->assertSame(1024, $width);
        $this->assertSame(768,  $height);

        ThumbGenerator::generate($sourcePath, $targetPath);

        list($width, $height) = getimagesize($targetPath);

        $this->assertSame('image/jpeg', File::mimeType($targetPath));
        $this->assertSame(60, $width);
        $this->assertSame(60,  $height);
    }
}
